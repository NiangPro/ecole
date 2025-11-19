<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class CodeExecutionService
{
    /**
     * Taille maximale du code (en caractères)
     */
    const MAX_CODE_SIZE = 10000;

    /**
     * Timeout d'exécution (en secondes)
     */
    const EXECUTION_TIMEOUT = 10;

    /**
     * Fonctions dangereuses Python
     */
    const DANGEROUS_PYTHON_FUNCTIONS = [
        'exec', 'eval', 'compile', '__import__', 'open', 'file',
        'input', 'raw_input', 'execfile', 'reload', '__builtin__',
        'subprocess', 'os.system', 'os.popen', 'popen2', 'commands',
        'socket', 'urllib', 'urllib2', 'httplib', 'ftplib', 'telnetlib',
        '__builtins__', 'globals', 'locals', 'vars', 'dir', 'getattr',
        'setattr', 'delattr', 'hasattr', '__getattribute__', '__setattr__',
        'importlib', 'imp', 'sys.modules', '__import__', 'builtins',
    ];

    /**
     * Modules Python dangereux
     */
    const DANGEROUS_PYTHON_MODULES = [
        'os', 'sys', 'subprocess', 'socket', 'urllib', 'urllib2',
        'httplib', 'ftplib', 'telnetlib', 'commands', 'popen2',
        'eval', 'exec', 'compile', 'importlib', 'imp', 'builtins',
    ];

    /**
     * Fonctions PHP dangereuses
     */
    const DANGEROUS_PHP_FUNCTIONS = [
        'exec', 'system', 'shell_exec', 'passthru', 'proc_open',
        'popen', 'curl_exec', 'file_get_contents', 'fopen', 'file',
        'readfile', 'include', 'require', 'include_once', 'require_once',
        'eval', 'create_function', 'preg_replace', 'call_user_func',
        'call_user_func_array', 'assert', 'unserialize', 'serialize',
    ];

    /**
     * Valider le code avant exécution
     */
    public static function validateCode(string $code, string $language): array
    {
        $errors = [];

        // Vérifier la taille
        if (strlen($code) > self::MAX_CODE_SIZE) {
            $errors[] = "Le code est trop long (maximum " . self::MAX_CODE_SIZE . " caractères)";
        }

        // Vérifier les fonctions dangereuses selon le langage
        if ($language === 'python') {
            foreach (self::DANGEROUS_PYTHON_FUNCTIONS as $func) {
                if (preg_match('/\b' . preg_quote($func, '/') . '\s*\(/i', $code)) {
                    $errors[] = "Fonction non autorisée détectée : {$func}(). Cette fonction est désactivée pour des raisons de sécurité.";
                }
            }

            // Vérifier les imports dangereux
            foreach (self::DANGEROUS_PYTHON_MODULES as $module) {
                if (preg_match('/\bimport\s+' . preg_quote($module, '/') . '\b/i', $code) ||
                    preg_match('/\bfrom\s+' . preg_quote($module, '/') . '\s+import/i', $code)) {
                    $errors[] = "Import non autorisé détecté : {$module}. Ce module est désactivé pour des raisons de sécurité.";
                }
            }
        } elseif ($language === 'php') {
            foreach (self::DANGEROUS_PHP_FUNCTIONS as $func) {
                if (preg_match('/\b' . preg_quote($func, '/') . '\s*\(/i', $code)) {
                    $errors[] = "Fonction non autorisée détectée : {$func}(). Cette fonction est désactivée pour des raisons de sécurité.";
                }
            }

            // Vérifier les includes/requires
            if (preg_match('/\b(include|require|include_once|require_once)\s*\(/i', $code)) {
                $errors[] = "Les fonctions include/require sont désactivées pour des raisons de sécurité.";
            }
        }

        return $errors;
    }

    /**
     * Logger une tentative d'exécution
     */
    public static function logExecution(string $language, string $code, ?string $error, string $ip): void
    {
        $context = [
            'language' => $language,
            'code_length' => strlen($code),
            'code_preview' => substr($code, 0, 200), // Premiers 200 caractères seulement
            'has_error' => !empty($error),
            'error' => $error ? substr($error, 0, 500) : null, // Limiter la taille de l'erreur
            'ip' => $ip,
            'timestamp' => now()->toIso8601String(),
        ];

        if (!empty($error)) {
            Log::warning('Code execution failed', $context);
        } else {
            Log::info('Code execution successful', $context);
        }
    }

    /**
     * Nettoyer les fichiers temporaires de manière sécurisée
     */
    public static function cleanupTempFiles(array $files): void
    {
        foreach ($files as $file) {
            if (file_exists($file)) {
                // Vérifier que le fichier est bien dans le répertoire temporaire
                $realPath = realpath($file);
                $tempDir = realpath(sys_get_temp_dir());
                
                if ($realPath && $tempDir && str_starts_with($realPath, $tempDir)) {
                    @unlink($file);
                } else {
                    Log::warning('Attempted to delete file outside temp directory', [
                        'file' => $file,
                        'real_path' => $realPath,
                        'temp_dir' => $tempDir,
                    ]);
                }
            }
        }
    }

    /**
     * Exécuter du code avec timeout
     */
    public static function executeWithTimeout(string $command, int $timeout = self::EXECUTION_TIMEOUT): array
    {
        $output = [];
        $returnValue = 0;
        
        // Utiliser proc_open pour avoir un meilleur contrôle
        $descriptorspec = [
            0 => ['pipe', 'r'],  // stdin
            1 => ['pipe', 'w'],  // stdout
            2 => ['pipe', 'w'],  // stderr
        ];

        $process = @proc_open($command, $descriptorspec, $pipes);
        
        if (!is_resource($process)) {
            return [
                'output' => [],
                'return_value' => -1,
                'error' => 'Impossible de démarrer le processus',
            ];
        }

        // Fermer stdin (pas d'entrée)
        fclose($pipes[0]);

        // Lire la sortie avec timeout
        $startTime = time();
        $stdout = '';
        $stderr = '';

        // Configurer les pipes en mode non-bloquant
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        while (true) {
            $status = proc_get_status($process);
            
            // Vérifier le timeout
            if (time() - $startTime > $timeout) {
                // Tuer le processus
                proc_terminate($process);
                proc_close($process);
                fclose($pipes[1]);
                fclose($pipes[2]);
                
                return [
                    'output' => [],
                    'return_value' => -1,
                    'error' => "Timeout : l'exécution a dépassé {$timeout} secondes",
                ];
            }

            // Lire stdout
            $read = fread($pipes[1], 8192);
            if ($read !== false) {
                $stdout .= $read;
            }

            // Lire stderr
            $read = fread($pipes[2], 8192);
            if ($read !== false) {
                $stderr .= $read;
            }

            // Vérifier si le processus est terminé
            if (!$status['running']) {
                break;
            }

            // Petite pause pour éviter de surcharger le CPU
            usleep(100000); // 0.1 seconde
        }

        // Fermer les pipes
        fclose($pipes[1]);
        fclose($pipes[2]);

        // Obtenir le code de retour
        $returnValue = proc_close($process);

        return [
            'output' => explode("\n", rtrim($stdout)),
            'return_value' => $returnValue,
            'error' => !empty($stderr) ? $stderr : null,
        ];
    }
}

