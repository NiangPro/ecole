<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormationProgress;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivity;
use App\Services\BadgeService;

class FormationProgressController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'formation_slug' => 'required|string',
            'section_id' => 'nullable|string',
            'completed' => 'boolean',
            'time_spent' => 'nullable|integer|min:0',
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $progress = FormationProgress::getOrCreate($user->id, $request->formation_slug);

        if ($request->has('section_id') && $request->has('completed')) {
            if ($request->completed) {
                $progress->markSectionAsCompleted($request->section_id);
            }
            $progress->section_id = $request->section_id;
        }

        if ($request->has('time_spent')) {
            $progress->addTimeSpent($request->time_spent);
        }

        // Calculer le pourcentage (basé sur les sections complétées)
        // Définir le nombre total de sections par formation
        $totalSectionsByFormation = [
            'html5' => 22, // intro, editors, basic, elements, attributes, headings, paragraphs, styles, formatting, quotations, comments, colors, links, images, tables, lists, forms, media, canvas, svg, apis, semantic
            'css3' => 14, // intro, syntax, selectors, colors, backgrounds, borders, margins, text, fonts, flexbox, grid, transitions, animations, responsive
            'javascript' => 13, // intro, variables, datatypes, operators, conditions, loops, functions, arrays, objects, dom, events, es6, async
            'php' => 14, // intro, syntax, variables, datatypes, operators, conditions, loops, functions, arrays, forms, sessions, mysql, pdo, oop
            'bootstrap' => 12, // intro, installation, grid, containers, typography, colors, buttons, navbar, cards, forms, modals, utilities
            'python' => 12, // intro, syntax, variables, datatypes, operators, conditions, loops, functions, lists, modules, oop, files
            'java' => 13, // intro, syntax, variables, datatypes, operators, conditions, loops, methods, arrays, oop, collections, exceptions, files
            'sql' => 13, // intro, syntax, datatypes, operators, select, where, conditions, joins, aggregate, groupby, insert, subqueries, tables
            'c' => 13, // intro, syntax, variables, datatypes, operators, conditions, loops, functions, pointers, arrays, structs, memory, files
            'git' => 12, // intro, install, config, init, status, commit, branches, merge, remote, push, clone, github
            'wordpress' => 13, // intro, install, dashboard, pages, posts, media, themes, plugins, menus, widgets, users, seo, security
            'ia' => 12, // intro, concepts, ml, dl, nlp, cv, python, tensorflow, pytorch, models, apis, ethics
            'dart' => 13, // intro, syntax, variables, datatypes, operators, conditions, loops, functions, classes, mixins, futures, streams, flutter
            'csharp' => 14, // intro, syntax, variables, datatypes, operators, conditions, loops, methods, classes, collections, inheritance, interfaces, linq, async
            'cpp' => 13, // intro, syntax, variables, datatypes, operators, conditions, loops, functions, classes, inheritance, polymorphism, templates, stl
        ];
        
        $totalSections = $totalSectionsByFormation[$request->formation_slug] ?? 10;
        $completedSections = count($progress->completed_sections ?? []);
        $previousPercentage = $progress->progress_percentage;
        $progress->updateProgress($totalSections, $completedSections);
        
        // Enregistrer l'activité si la progression a changé significativement
        if ($progress->progress_percentage > $previousPercentage) {
            $formationName = ucfirst(str_replace('-', ' ', $request->formation_slug));
            
            // Enregistrer l'activité
            UserActivity::log(
                $user->id,
                'formation',
                'Formation : ' . $formationName,
                "formations/{$request->formation_slug}",
                [
                    'progress_percentage' => $progress->progress_percentage,
                    'sections_completed' => $completedSections,
                    'total_sections' => $totalSections,
                    'time_spent_minutes' => $progress->time_spent_minutes,
                ]
            );
            
            // Vérifier et attribuer des badges
            $badgeService = new BadgeService();
            $badgeService->checkAndAwardBadges($user);
            
            // Invalider le cache du dashboard
            \App\Http\Controllers\ProfileController::clearCache($user->id);
        }

        return response()->json([
            'progress_percentage' => $progress->progress_percentage,
            'completed_sections' => $progress->completed_sections,
            'time_spent_minutes' => $progress->time_spent_minutes,
        ]);
    }

    public function get($formationSlug)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['progress_percentage' => 0]);
        }

        $progress = $user->getProgressForFormation($formationSlug);

        if (!$progress) {
            return response()->json([
                'progress_percentage' => 0,
                'completed_sections' => [],
                'time_spent_minutes' => 0,
            ]);
        }

        return response()->json([
            'progress_percentage' => $progress->progress_percentage,
            'completed_sections' => $progress->completed_sections ?? [],
            'time_spent_minutes' => $progress->time_spent_minutes,
            'started_at' => $progress->started_at,
            'completed_at' => $progress->completed_at,
        ]);
    }
}
