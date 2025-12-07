<?php

/**
 * Script de génération automatique du CHANGELOG.md
 * 
 * Ce script analyse les commits Git et génère automatiquement
 * un fichier CHANGELOG.md basé sur les messages de commit.
 * 
 * Usage: php scripts/generate-changelog.php [version]
 */

if (php_sapi_name() !== 'cli') {
    die('Ce script doit être exécuté en ligne de commande.');
}

$version = $argv[1] ?? 'Unreleased';
$changelogFile = __DIR__ . '/../CHANGELOG.md';

// Catégories de commits
$categories = [
    '✨ Ajouté' => ['feat:', 'add:', 'new:'],
    '🔧 Modifié' => ['refactor:', 'update:', 'modify:', 'change:'],
    '🐛 Corrigé' => ['fix:', 'bugfix:', 'patch:'],
    '🔒 Sécurité' => ['security:', 'sec:'],
    '🗑️ Supprimé' => ['remove:', 'delete:', 'drop:'],
    '📚 Documentation' => ['docs:', 'doc:'],
    '🧪 Tests' => ['test:', 'tests:'],
    '⚡ Performance' => ['perf:', 'performance:', 'optimize:'],
];

// Obtenir les commits depuis la dernière version
$lastTag = exec('git describe --tags --abbrev=0 2>/dev/null', $output, $return);
$since = $lastTag ? $lastTag : 'HEAD~50';

$commits = [];
exec("git log {$since}..HEAD --pretty=format:'%h|%s|%an|%ad' --date=short", $commits);

$changelog = [];
$changelog['Unreleased'] = [];

foreach ($commits as $commit) {
    [$hash, $message, $author, $date] = explode('|', $commit);
    
    $categorized = false;
    foreach ($categories as $category => $prefixes) {
        foreach ($prefixes as $prefix) {
            if (stripos($message, $prefix) === 0) {
                $cleanMessage = trim(substr($message, strlen($prefix)));
                $changelog['Unreleased'][$category][] = "- {$cleanMessage} ([{$hash}](https://github.com/votre-repo/formation-laravel/commit/{$hash}))";
                $categorized = true;
                break 2;
            }
        }
    }
    
    if (!$categorized) {
        $changelog['Unreleased']['🔧 Modifié'][] = "- {$message} ([{$hash}](https://github.com/votre-repo/formation-laravel/commit/{$hash}))";
    }
}

// Générer le contenu du CHANGELOG
$content = "# 📝 Changelog - NiangProgrammeur\n\n";
$content .= "Tous les changements notables de ce projet seront documentés dans ce fichier.\n\n";
$content .= "Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),\n";
$content .= "et ce projet adhère au [Semantic Versioning](https://semver.org/lang/fr/).\n\n";

// Section Unreleased
if (!empty($changelog['Unreleased'])) {
    $content .= "## [Unreleased]\n\n";
    
    foreach ($categories as $category => $prefixes) {
        if (isset($changelog['Unreleased'][$category]) && !empty($changelog['Unreleased'][$category])) {
            $content .= "### {$category}\n\n";
            foreach ($changelog['Unreleased'][$category] as $item) {
                $content .= "{$item}\n";
            }
            $content .= "\n";
        }
    }
}

// Lire le CHANGELOG existant pour préserver les versions précédentes
if (file_exists($changelogFile)) {
    $existingContent = file_get_contents($changelogFile);
    
    // Extraire les sections de version existantes (après [Unreleased])
    if (preg_match('/## \[Unreleased\].*?## \[(\d+\.\d+\.\d+)\]/s', $existingContent, $matches)) {
        $versionSection = $matches[1];
        // Extraire tout après [Unreleased]
        if (preg_match('/## \[Unreleased\].*?## \[(\d+\.\d+\.\d+)\]/s', $existingContent, $matches)) {
            $oldVersions = substr($existingContent, strpos($existingContent, "## [{$matches[1]}]"));
            $content .= $oldVersions;
        }
    } elseif (preg_match('/## \[(\d+\.\d+\.\d+)\]/', $existingContent, $matches)) {
        // Si pas de section Unreleased, prendre à partir de la première version
        $oldVersions = substr($existingContent, strpos($existingContent, "## [{$matches[1]}]"));
        $content .= "\n" . $oldVersions;
    }
} else {
    // Si le fichier n'existe pas, ajouter un exemple
    $content .= "## [1.0.0] - " . date('Y-m-d') . "\n\n";
    $content .= "### ✨ Ajouté\n\n";
    $content .= "- Version initiale du projet\n\n";
}

// Ajouter le footer
$content .= "\n---\n\n";
$content .= "**Dernière mise à jour** : " . date('Y-m-d') . "\n";

// Écrire le fichier
file_put_contents($changelogFile, $content);

echo "✅ CHANGELOG.md généré avec succès !\n";
echo "📊 " . count($commits) . " commits analysés\n";

// Afficher un résumé
foreach ($categories as $category => $prefixes) {
    if (isset($changelog['Unreleased'][$category]) && !empty($changelog['Unreleased'][$category])) {
        echo "   {$category}: " . count($changelog['Unreleased'][$category]) . " changements\n";
    }
}

