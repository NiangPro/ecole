// Script pour l'éditeur d'articles avec calcul SEO et lisibilité en temps réel
(function() {
    'use strict';
    
    const titleInput = document.getElementById('articleTitle');
    const contentTextarea = document.getElementById('articleContent');
    const excerptTextarea = document.getElementById('articleExcerpt');
    const metaTitleInput = document.getElementById('metaTitle');
    const metaDescTextarea = document.getElementById('metaDescription');
    const wordCountSpan = document.getElementById('wordCount');
    const wordLabel = document.getElementById('wordLabel');
    
    // Fonction pour compter les mots (identique au serveur avec str_word_count)
    function countWords(text) {
        if (!text) return 0;
        // Enlever les balises HTML comme le fait strip_tags côté serveur
        const cleanText = text.replace(/<[^>]*>/g, '');
        // Utiliser la même logique que str_word_count PHP
        // str_word_count compte les séquences de lettres et chiffres séparées par des espaces/punctuation
        const words = cleanText.match(/[\p{L}\p{N}]+/gu);
        return words ? words.length : 0;
    }
    
    // Fonction pour calculer le score SEO
    function calculateSeoScore() {
        let score = 0;
        const maxScore = 100;
        
        // Titre (20 points)
        const title = titleInput ? titleInput.value : '';
        if (title.length >= 30 && title.length <= 60) {
            score += 20;
        } else if (title.length > 0) {
            score += 10;
        }
        
        // Meta title (15 points)
        const metaTitle = metaTitleInput ? metaTitleInput.value : '';
        if (metaTitle.length >= 30 && metaTitle.length <= 60) {
            score += 15;
        } else if (metaTitle.length > 0) {
            score += 7;
        }
        
        // Meta description (15 points)
        const metaDesc = metaDescTextarea ? metaDescTextarea.value : '';
        if (metaDesc.length >= 120 && metaDesc.length <= 160) {
            score += 15;
        } else if (metaDesc.length > 0) {
            score += 7;
        }
        
        // Contenu (20 points)
        const content = contentTextarea ? contentTextarea.value : '';
        const wordCount = countWords(content);
        if (wordCount >= 300) {
            score += 20;
        } else if (wordCount >= 150) {
            score += 10;
        } else if (wordCount > 0) {
            score += 5;
        }
        
        // Extrait (10 points)
        const excerpt = excerptTextarea ? excerptTextarea.value : '';
        if (excerpt.length >= 100) {
            score += 10;
        } else if (excerpt.length > 0) {
            score += 5;
        }
        
        // Mots-clés (10 points)
        const keywordsInput = document.querySelector('input[name="meta_keywords"]');
        if (keywordsInput && keywordsInput.value) {
            const keywords = keywordsInput.value.split(',').map(k => k.trim()).filter(k => k.length > 0);
            if (keywords.length >= 3 && keywords.length <= 10) {
                score += 10;
            } else if (keywords.length > 0) {
                score += 5;
            }
        }
        
        // Image de couverture (10 points)
        const coverTypeSelect = document.getElementById('coverType');
        const coverType = coverTypeSelect ? coverTypeSelect.value : 'internal';
        const coverImageFile = document.getElementById('coverImageFile');
        const coverImageUrl = document.getElementById('coverImageUrl');
        const previewImg = document.getElementById('previewImg');
        // Vérifier si une image existe (fichier uploadé, URL remplie, ou image existante affichée)
        let hasCover = false;
        if (coverType === 'external') {
            hasCover = coverImageUrl && coverImageUrl.value && coverImageUrl.value.trim().length > 0;
        } else {
            // Pour interne : vérifier si un fichier est uploadé OU si une image existe déjà (via preview)
            hasCover = (coverImageFile && coverImageFile.files && coverImageFile.files.length > 0) ||
                       (previewImg && previewImg.src && previewImg.src.length > 0 && 
                        !previewImg.src.includes('data:image') && 
                        !previewImg.src.includes('about:blank'));
        }
        if (hasCover) {
            score += 10;
        }
        
        return Math.min(score, maxScore);
    }
    
    // Fonction pour calculer le score de lisibilité
    function calculateReadabilityScore() {
        const content = contentTextarea ? contentTextarea.value : '';
        if (!content || content.trim().length === 0) {
            return 0;
        }
        
        const text = content.replace(/<[^>]*>/g, ''); // Enlever les balises HTML
        const words = text.trim().split(/\s+/).filter(word => word.length > 0);
        const sentences = text.split(/[.!?]+/).filter(s => s.trim().length > 0);
        const paragraphs = text.split(/\n\s*\n/).filter(p => p.trim().length > 0);
        
        if (sentences.length === 0 || words.length === 0) {
            return 0;
        }
        
        const avgWordsPerSentence = words.length / sentences.length;
        const avgSentencesPerParagraph = paragraphs.length > 0 ? sentences.length / paragraphs.length : 0;
        
        let score = 100;
        
        // Pénalité pour phrases trop longues
        if (avgWordsPerSentence > 20) {
            score -= 20;
        } else if (avgWordsPerSentence > 15) {
            score -= 10;
        }
        
        // Bonus pour phrases courtes
        if (avgWordsPerSentence >= 10 && avgWordsPerSentence <= 15) {
            score += 10;
        }
        
        // Pénalité pour paragraphes trop longs
        if (avgSentencesPerParagraph > 5) {
            score -= 15;
        }
        
        // Bonus pour structure équilibrée
        if (paragraphs.length >= 3 && paragraphs.length <= 10) {
            score += 10;
        }
        
        return Math.max(0, Math.min(100, score));
    }
    
    // Fonction pour mettre à jour les scores
    function updateScores() {
        const seoScore = calculateSeoScore();
        const readabilityScore = calculateReadabilityScore();
        
        // Mettre à jour le score SEO
        const seoScoreEl = document.getElementById('seoScore');
        const seoBarEl = document.getElementById('seoBar');
        if (seoScoreEl) seoScoreEl.textContent = seoScore;
        if (seoBarEl) seoBarEl.style.width = seoScore + '%';
        
        // Mettre à jour le score de lisibilité
        const readabilityScoreEl = document.getElementById('readabilityScore');
        const readabilityBarEl = document.getElementById('readabilityBar');
        if (readabilityScoreEl) readabilityScoreEl.textContent = readabilityScore;
        if (readabilityBarEl) readabilityBarEl.style.width = readabilityScore + '%';
        
        // Mettre à jour le compteur de mots en temps réel
        if (wordCountSpan && contentTextarea) {
            const wordCount = countWords(contentTextarea.value);
            wordCountSpan.textContent = wordCount;
            if (wordLabel) {
                wordLabel.textContent = wordCount <= 1 ? 'mot' : 'mots';
            }
        }
    }
    
    // Fonction pour mettre à jour les longueurs de meta
    function updateMetaLengths() {
        if (metaTitleInput) {
            const metaTitleLength = document.getElementById('metaTitleLength');
            if (metaTitleLength) {
                metaTitleLength.textContent = metaTitleInput.value.length;
            }
        }
        
        if (metaDescTextarea) {
            const metaDescLength = document.getElementById('metaDescLength');
            if (metaDescLength) {
                metaDescLength.textContent = metaDescTextarea.value.length;
            }
        }
    }
    
    // Écouter les changements
    if (titleInput) {
        titleInput.addEventListener('input', () => {
            updateScores();
            updateDetailedScores();
            // Auto-remplir meta title si vide
            if (metaTitleInput && !metaTitleInput.value) {
                metaTitleInput.value = titleInput.value.substring(0, 60);
                updateMetaLengths();
                updateDetailedScores();
            }
        });
    }
    
    if (contentTextarea) {
        contentTextarea.addEventListener('input', () => {
            updateScores();
            updateDetailedScores();
            // Mettre à jour le compteur de mots en temps réel
            if (wordCountSpan) {
                const wordCount = countWords(contentTextarea.value);
                wordCountSpan.textContent = wordCount;
                if (wordLabel) {
                    wordLabel.textContent = wordCount <= 1 ? 'mot' : 'mots';
                }
            }
        });
    }
    
    if (excerptTextarea) {
        excerptTextarea.addEventListener('input', () => {
            updateScores();
            updateDetailedScores();
        });
    }
    
    if (metaTitleInput) {
        metaTitleInput.addEventListener('input', () => {
            updateScores();
            updateMetaLengths();
            updateDetailedScores();
        });
    }
    
    if (metaDescTextarea) {
        metaDescTextarea.addEventListener('input', () => {
            updateScores();
            updateMetaLengths();
            updateDetailedScores();
        });
    }
    
    // Gérer le changement de type d'image
    function initCoverTypeHandler() {
        const coverTypeSelect = document.getElementById('coverType');
        if (!coverTypeSelect) return;
        
        coverTypeSelect.addEventListener('change', function() {
            const internalDiv = document.getElementById('internalImage');
            const externalDiv = document.getElementById('externalImage');
            const coverImageFile = document.getElementById('coverImageFile');
            const coverImageUrl = document.getElementById('coverImageUrl');
            const coverPreview = document.getElementById('coverPreview');
            const previewImg = document.getElementById('previewImg');
            
            if (this.value === 'internal') {
                // Afficher le champ file, masquer le champ URL
                if (internalDiv) {
                    internalDiv.style.display = 'block';
                }
                if (externalDiv) {
                    externalDiv.style.display = 'none';
                }
                // Réinitialiser les valeurs
                if (coverImageUrl) {
                    coverImageUrl.value = '';
                }
                if (coverPreview && previewImg) {
                    coverPreview.classList.add('hidden');
                    previewImg.src = '';
                }
            } else if (this.value === 'external') {
                // Afficher le champ URL, masquer le champ file
                if (internalDiv) {
                    internalDiv.style.display = 'none';
                }
                if (externalDiv) {
                    externalDiv.style.display = 'block';
                }
                // Réinitialiser les valeurs
                if (coverImageFile) {
                    coverImageFile.value = '';
                }
                if (coverPreview && previewImg) {
                    coverPreview.classList.add('hidden');
                    previewImg.src = '';
                }
            }
            updateScores();
            updateDetailedScores();
        });
    }
    
    // Initialiser le handler du type d'image
    initCoverTypeHandler();
    
    // Gérer l'upload d'image
    const coverImageFile = document.getElementById('coverImageFile');
    if (coverImageFile) {
        coverImageFile.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const previewDiv = document.getElementById('coverPreview');
                    const previewImg = document.getElementById('previewImg');
                    if (previewDiv && previewImg) {
                        previewImg.src = event.target.result;
                        previewDiv.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(e.target.files[0]);
                updateScores();
                updateDetailedScores();
            }
        });
    }
    
    // Gérer l'URL d'image externe
    const coverImageUrl = document.getElementById('coverImageUrl');
    if (coverImageUrl) {
        coverImageUrl.addEventListener('input', function() {
            if (this.value) {
                const previewDiv = document.getElementById('coverPreview');
                const previewImg = document.getElementById('previewImg');
                if (previewDiv && previewImg) {
                    previewImg.src = this.value;
                    previewDiv.classList.remove('hidden');
                }
            } else {
                const previewDiv = document.getElementById('coverPreview');
                if (previewDiv) previewDiv.classList.add('hidden');
            }
            updateScores();
            updateDetailedScores();
        });
    }
    
    // Fonction pour mettre à jour les scores détaillés
    function updateDetailedScores() {
        const seoScore = calculateSeoScore();
        const readabilityScore = calculateReadabilityScore();
        
        // Mettre à jour les scores détaillés
        const seoScoreDetail = document.getElementById('seoScoreDetail');
        const seoBarDetail = document.getElementById('seoBarDetail');
        if (seoScoreDetail) seoScoreDetail.textContent = seoScore + '/100';
        if (seoBarDetail) seoBarDetail.style.width = seoScore + '%';
        
        const readabilityScoreDetail = document.getElementById('readabilityScoreDetail');
        const readabilityBarDetail = document.getElementById('readabilityBarDetail');
        if (readabilityScoreDetail) readabilityScoreDetail.textContent = readabilityScore + '/100';
        if (readabilityBarDetail) readabilityBarDetail.style.width = readabilityScore + '%';
        
        // Vérifications SEO détaillées
        const title = titleInput ? titleInput.value : '';
        const titleLength = title.length;
        const titleCheck = document.getElementById('titleCheck');
        if (titleCheck) {
            if (titleLength >= 30 && titleLength <= 60) {
                titleCheck.innerHTML = '<i class="fas fa-check text-green-400"></i>';
            } else if (titleLength > 0) {
                titleCheck.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-400"></i>';
            } else {
                titleCheck.innerHTML = '<i class="fas fa-times text-red-400"></i>';
            }
        }
        
        const metaTitle = metaTitleInput ? metaTitleInput.value : '';
        const metaTitleLength = metaTitle.length;
        const metaTitleCheck = document.getElementById('metaTitleCheck');
        if (metaTitleCheck) {
            if (metaTitleLength >= 30 && metaTitleLength <= 60) {
                metaTitleCheck.innerHTML = '<i class="fas fa-check text-green-400"></i>';
            } else if (metaTitleLength > 0) {
                metaTitleCheck.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-400"></i>';
            } else {
                metaTitleCheck.innerHTML = '<i class="fas fa-times text-red-400"></i>';
            }
        }
        
        const metaDesc = metaDescTextarea ? metaDescTextarea.value : '';
        const metaDescLength = metaDesc.length;
        const metaDescCheck = document.getElementById('metaDescCheck');
        if (metaDescCheck) {
            if (metaDescLength >= 120 && metaDescLength <= 160) {
                metaDescCheck.innerHTML = '<i class="fas fa-check text-green-400"></i>';
            } else if (metaDescLength > 0) {
                metaDescCheck.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-400"></i>';
            } else {
                metaDescCheck.innerHTML = '<i class="fas fa-times text-red-400"></i>';
            }
        }
        
        const content = contentTextarea ? contentTextarea.value : '';
        const wordCount = countWords(content);
        const contentCheck = document.getElementById('contentCheck');
        if (contentCheck) {
            if (wordCount >= 300) {
                contentCheck.innerHTML = '<i class="fas fa-check text-green-400"></i>';
            } else if (wordCount >= 150) {
                contentCheck.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-400"></i>';
            } else if (wordCount > 0) {
                contentCheck.innerHTML = '<i class="fas fa-times text-red-400"></i>';
            } else {
                contentCheck.innerHTML = '<i class="fas fa-times text-red-400"></i>';
            }
        }
        
        const excerpt = excerptTextarea ? excerptTextarea.value : '';
        const excerptCheck = document.getElementById('excerptCheck');
        if (excerptCheck) {
            if (excerpt.length >= 100) {
                excerptCheck.innerHTML = '<i class="fas fa-check text-green-400"></i>';
            } else if (excerpt.length > 0) {
                excerptCheck.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-400"></i>';
            } else {
                excerptCheck.innerHTML = '<i class="fas fa-times text-red-400"></i>';
            }
        }
        
        const coverTypeSelect = document.getElementById('coverType');
        const coverType = coverTypeSelect ? coverTypeSelect.value : 'internal';
        const coverImageFile = document.getElementById('coverImageFile');
        const coverImageUrl = document.getElementById('coverImageUrl');
        const previewImg = document.getElementById('previewImg');
        // Vérifier si une image existe (fichier uploadé, URL remplie, ou image existante affichée)
        let hasCover = false;
        if (coverType === 'external') {
            hasCover = coverImageUrl && coverImageUrl.value && coverImageUrl.value.trim().length > 0;
        } else {
            // Pour interne : vérifier si un fichier est uploadé OU si une image existe déjà (via preview)
            hasCover = (coverImageFile && coverImageFile.files && coverImageFile.files.length > 0) ||
                       (previewImg && previewImg.src && previewImg.src.length > 0 && 
                        !previewImg.src.includes('data:image') && 
                        !previewImg.src.includes('about:blank'));
        }
        const imageCheck = document.getElementById('imageCheck');
        if (imageCheck) {
            if (hasCover) {
                imageCheck.innerHTML = '<i class="fas fa-check text-green-400"></i>';
            } else {
                imageCheck.innerHTML = '<i class="fas fa-times text-red-400"></i>';
            }
        }
        
        const keywordsInput = document.querySelector('input[name="meta_keywords"]');
        const keywords = keywordsInput ? keywordsInput.value.split(',').filter(k => k.trim()).length : 0;
        const keywordsCheck = document.getElementById('keywordsCheck');
        if (keywordsCheck) {
            if (keywords >= 3 && keywords <= 10) {
                keywordsCheck.innerHTML = '<i class="fas fa-check text-green-400"></i>';
            } else if (keywords > 0) {
                keywordsCheck.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-400"></i>';
            } else {
                keywordsCheck.innerHTML = '<i class="fas fa-times text-red-400"></i>';
            }
        }
        
        // Détails lisibilité
        if (content) {
            const text = content.replace(/<[^>]*>/g, '');
            const words = text.trim().split(/\s+/).filter(word => word.length > 0);
            const sentences = text.split(/[.!?]+/).filter(s => s.trim().length > 0);
            const paragraphs = text.split(/\n\s*\n/).filter(p => p.trim().length > 0);
            
            const avgWordsPerSentence = sentences.length > 0 ? words.length / sentences.length : 0;
            const avgSentencesPerParagraph = paragraphs.length > 0 ? sentences.length / paragraphs.length : 0;
            
            const wordsPerSentenceEl = document.getElementById('wordsPerSentence');
            if (wordsPerSentenceEl) {
                wordsPerSentenceEl.textContent = avgWordsPerSentence.toFixed(1);
                wordsPerSentenceEl.className = avgWordsPerSentence >= 10 && avgWordsPerSentence <= 15 ? 'text-green-400' : 'text-gray-300';
            }
            
            const sentencesPerParagraphEl = document.getElementById('sentencesPerParagraph');
            if (sentencesPerParagraphEl) {
                sentencesPerParagraphEl.textContent = avgSentencesPerParagraph.toFixed(1);
                sentencesPerParagraphEl.className = avgSentencesPerParagraph <= 5 ? 'text-green-400' : 'text-gray-300';
            }
            
            const paragraphCountEl = document.getElementById('paragraphCount');
            if (paragraphCountEl) {
                paragraphCountEl.textContent = paragraphs.length;
                paragraphCountEl.className = paragraphs.length >= 3 && paragraphs.length <= 10 ? 'text-green-400' : 'text-gray-300';
            }
        }
    }
    
    // Écouter les changements pour les scores détaillés
    if (titleInput) {
        titleInput.addEventListener('input', updateDetailedScores);
    }
    if (contentTextarea) {
        contentTextarea.addEventListener('input', updateDetailedScores);
    }
    if (excerptTextarea) {
        excerptTextarea.addEventListener('input', updateDetailedScores);
    }
    if (metaTitleInput) {
        metaTitleInput.addEventListener('input', updateDetailedScores);
    }
    if (metaDescTextarea) {
        metaDescTextarea.addEventListener('input', updateDetailedScores);
    }
    const keywordsInput = document.querySelector('input[name="meta_keywords"]');
    if (keywordsInput) {
        keywordsInput.addEventListener('input', updateDetailedScores);
    }
    
    // Fonction d'initialisation complète
    function initializeEditor() {
        // Initialiser les scores
        updateScores();
        updateMetaLengths();
        updateDetailedScores();
        
        // Mettre à jour le compteur de mots au chargement
        if (wordCountSpan && contentTextarea) {
            const wordCount = countWords(contentTextarea.value);
            wordCountSpan.textContent = wordCount;
            if (wordLabel) {
                wordLabel.textContent = wordCount <= 1 ? 'mot' : 'mots';
            }
        }
        
        // Réinitialiser le handler du type d'image au cas où
        initCoverTypeHandler();
    }
    
    // Initialiser quand le DOM est prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeEditor);
    } else {
        initializeEditor();
    }
    
    // Réessayer après le chargement complet
    window.addEventListener('load', function() {
        setTimeout(initializeEditor, 300);
    });
    
    // Fonctions de formatage de texte
    window.formatText = function(command) {
        if (!contentTextarea) return;
        
        const start = contentTextarea.selectionStart;
        const end = contentTextarea.selectionEnd;
        const selectedText = contentTextarea.value.substring(start, end);
        const before = contentTextarea.value.substring(0, start);
        const after = contentTextarea.value.substring(end);
        
        let formattedText = '';
        switch(command) {
            case 'bold':
                formattedText = selectedText ? `**${selectedText}**` : '****';
                break;
            case 'italic':
                formattedText = selectedText ? `*${selectedText}*` : '**';
                break;
            case 'underline':
                formattedText = selectedText ? `<u>${selectedText}</u>` : '<u></u>';
                break;
        }
        
        contentTextarea.value = before + formattedText + after;
        contentTextarea.focus();
        contentTextarea.setSelectionRange(start + (formattedText.length - selectedText.length), start + formattedText.length);
        updateScores();
        updateDetailedScores();
        // Mettre à jour le compteur de mots
        if (wordCountSpan) {
            const wordCount = countWords(contentTextarea.value);
            wordCountSpan.textContent = wordCount;
            if (wordLabel) {
                wordLabel.textContent = wordCount <= 1 ? 'mot' : 'mots';
            }
        }
    };
    
    window.insertLink = function() {
        if (!contentTextarea) return;
        const url = prompt('Entrez l\'URL du lien:');
        if (url) {
            const start = contentTextarea.selectionStart;
            const selectedText = contentTextarea.value.substring(contentTextarea.selectionStart, contentTextarea.selectionEnd);
            const linkText = selectedText || 'Texte du lien';
            const link = `[${linkText}](${url})`;
            contentTextarea.value = contentTextarea.value.substring(0, start) + link + contentTextarea.value.substring(contentTextarea.selectionEnd);
            contentTextarea.focus();
            updateScores();
            updateDetailedScores();
            // Mettre à jour le compteur de mots
            if (wordCountSpan) {
                const wordCount = countWords(contentTextarea.value);
                wordCountSpan.textContent = wordCount;
                if (wordLabel) {
                    wordLabel.textContent = wordCount <= 1 ? 'mot' : 'mots';
                }
            }
        }
    };
    
    window.insertList = function(type) {
        if (!contentTextarea) return;
        const start = contentTextarea.selectionStart;
        const listItem = type === 'ul' ? '- ' : '1. ';
        contentTextarea.value = contentTextarea.value.substring(0, start) + listItem + contentTextarea.value.substring(start);
        contentTextarea.focus();
        contentTextarea.setSelectionRange(start + listItem.length, start + listItem.length);
        updateScores();
        updateDetailedScores();
        // Mettre à jour le compteur de mots
        if (wordCountSpan) {
            const wordCount = countWords(contentTextarea.value);
            wordCountSpan.textContent = wordCount;
            if (wordLabel) {
                wordLabel.textContent = wordCount <= 1 ? 'mot' : 'mots';
            }
        }
    };
})();

