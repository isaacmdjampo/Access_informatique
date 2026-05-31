-- ============================================================
-- ACCESS INFORMATIQUE — DONNÉES INITIALES (SEEDS)
-- À exécuter APRÈS schema.sql sur la base vide
-- ============================================================
-- Contient toutes les données issues des fichiers Vue.js :
--   src/data/solutionsDetailData.js
--   src/data/formations.js
--   src/data/hackathon.js
--   src/data/aboutData.js
--   src/pages/*.vue (textes statiques)
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- TEXTES STATIQUES DU SITE (table contents)
-- Groupés par page pour faciliter la lecture dans l'admin
-- ============================================================

-- NOTE : Le compte admin est créé séparément via setup_admin.php
--        pour générer un vrai hash bcrypt PHP.

INSERT INTO `contents` (`page`, `key_name`, `label`, `value`) VALUES

-- ---- PAGE : global (header + footer, communs à tout le site) ----
('global', 'header.badge',     'Badge d\'accueil (bandeau vert)',  'Bienvenue chez Access Informatique'),
('global', 'header.email',     'Email affiché dans le header',     'info@accessinformatique.com'),
('global', 'header.phone1',    'Téléphone 1',                      '(+225) 01 01 57 30 54'),
('global', 'header.phone2',    'Téléphone 2',                      '(+225) 07 07 26 18 58'),
('global', 'footer.tagline',   'Tagline du footer',                'Éditeur de solutions de gestion sur mesure pour les entreprises, institutions et professionnels de Côte d\'Ivoire et d\'Afrique.'),
('global', 'footer.address',   'Adresse',                          'Yopougon Sable, Andokoi, Abidjan, Côte d\'Ivoire'),
('global', 'footer.hours',     'Horaires d\'ouverture',            'Lun–Ven : 08h–18h · Sam : 09h–13h'),

-- ---- PAGE : home (Accueil.vue) ----
('home', 'hero.title',              'Titre principal (hero)',            'Des logiciels taillés pour vous.'),
('home', 'hero.subtitle',           'Sous-titre (hero)',                 'Access Informatique conçoit des solutions de gestion sur mesure pour les particuliers et les professionnels qui veulent aller plus loin.'),
('home', 'hero.btn.solutions',      'Bouton — Découvrir nos solutions',  'Découvrir nos solutions'),
('home', 'hero.btn.contact',        'Bouton — Nous contacter',           'Nous contacter'),
('home', 'stats.experience.value',  'Stat — Valeur Expérience',          '+20'),
('home', 'stats.experience.label',  'Stat — Label Expérience',           'Ans d\'expérience'),
('home', 'stats.solutions.value',   'Stat — Valeur Solutions',           '6'),
('home', 'stats.solutions.label',   'Stat — Label Solutions',            'Solutions actives'),
('home', 'stats.custom.value',      'Stat — Valeur Sur mesure',          '100%'),
('home', 'stats.custom.label',      'Stat — Label Sur mesure',           'Sur mesure'),
('home', 'why.title',               'Titre section "Pourquoi"',          'Pourquoi Access Informatique ?'),
('home', 'pillar1.title',           'Pilier 1 — Titre',                  'Développement sur mesure'),
('home', 'pillar1.body',            'Pilier 1 — Description',            'Chaque logiciel est conçu pour correspondre exactement à vos processus métier, sans fonctionnalités imposées inutiles.'),
('home', 'pillar2.title',           'Pilier 2 — Titre',                  'Conformité locale'),
('home', 'pillar2.body',            'Pilier 2 — Description',            'Nos solutions respectent les normes ivoiriennes (SYSCOHADA, CNPS, DGI) et sont maintenues à jour.'),
('home', 'pillar3.title',           'Pilier 3 — Titre',                  'Support humain permanent'),
('home', 'pillar3.body',            'Pilier 3 — Description',            'Formation incluse et assistance technique du lundi au samedi par une équipe locale.'),
('home', 'cta.title',               'Titre CTA bas de page',             'Prêt à transformer votre gestion ?'),
('home', 'cta.button',              'Bouton CTA — Parler à un expert',   'Parler à un expert'),

-- ---- PAGE : about (Apropos.vue) ----
('about', 'hero.title',             'Titre principal (hero)',             'Nous développons des solutions utiles.'),
('about', 'hero.body',              'Corps du hero',                      'Depuis plus de 20 ans, Access Informatique accompagne les entreprises, écoles, cliniques, mutuelles et institutions avec des logiciels conçus spécialement pour leurs réalités métiers.'),
('about', 'stats.experience.value', 'Stat — Valeur Expérience',           '+20'),
('about', 'stats.experience.label', 'Stat — Label Expérience',            'Ans d\'expérience'),
('about', 'stats.solutions.value',  'Stat — Valeur Solutions',            '6'),
('about', 'stats.solutions.label',  'Stat — Label Solutions',             'Solutions métiers'),
('about', 'stats.custom.value',     'Stat — Valeur Sur mesure',           '100%'),
('about', 'stats.custom.label',     'Stat — Label Sur mesure',            'Sur mesure'),
('about', 'expertise.title',        'Titre section expertise',            'Une expertise construite sur le terrain'),
('about', 'cta.text',               'CTA bas de page',                    'Construisons ensemble votre futur digital.'),
('about', 'cta.button',             'Bouton CTA',                         'Explorer les solutions'),

-- ---- PAGE : contact (Contact.vue) ----
('contact', 'hero.title',    'Titre principal (hero)',   'Parlons de votre projet.'),
('contact', 'hero.body',     'Corps du hero',            'Notre équipe vous accompagne dans la conception de vos solutions logicielles, applications métiers et systèmes de gestion.'),
('contact', 'form.btn',      'Bouton — Envoyer',         'Envoyer le message'),
('contact', 'info.title',    'Titre bloc informations',  'Nos coordonnées'),

-- ---- PAGE : formation (Formation.vue) ----
('formation', 'hero.title',              'Titre principal (hero)',    'Formez-vous aux métiers du numérique.'),
('formation', 'hero.body',               'Corps du hero',             'Des formations modernes, pratiques et professionnalisantes pour étudiants, entreprises et particuliers. Développement web, bureautique, design, cybersécurité, intelligence artificielle et bien plus.'),
('formation', 'stats.learners.value',    'Stat — Valeur Apprenants',  '+500'),
('formation', 'stats.learners.label',    'Stat — Label Apprenants',   'Apprenants'),
('formation', 'stats.courses.value',     'Stat — Valeur Formations',  '15+'),
('formation', 'stats.courses.label',     'Stat — Label Formations',   'Formations'),
('formation', 'stats.practical.value',   'Stat — Valeur Pratique',    '100%'),
('formation', 'stats.practical.label',   'Stat — Label Pratique',     'Pratique'),
('formation', 'stats.support.value',     'Stat — Valeur Support',     '24h'),
('formation', 'stats.support.label',     'Stat — Label Support',      'Support'),
('formation', 'cta.text',                'CTA bas de page',           'Prêt à apprendre les compétences du futur ?'),
('formation', 'cta.button',              'Bouton CTA',                'Voir toutes les formations'),

-- ---- PAGE : hackathon (Hackathon.vue) ----
('hackathon', 'hero.title',             'Titre principal (hero)',     'Construisons les solutions numériques de demain.'),
('hackathon', 'hero.body',              'Corps du hero',              'Rejoignez développeurs, designers, étudiants et innovateurs durant 48h de création intensive autour des technologies du futur.'),
('hackathon', 'stats.duration.value',   'Stat — Durée valeur',        '48H'),
('hackathon', 'stats.duration.label',   'Stat — Durée label',         'Challenge'),
('hackathon', 'stats.participants.value','Stat — Participants valeur', '200+'),
('hackathon', 'stats.participants.label','Stat — Participants label',  'Participants'),
('hackathon', 'stats.teams.value',      'Stat — Équipes valeur',      '20'),
('hackathon', 'stats.teams.label',      'Stat — Équipes label',       'Équipes'),
('hackathon', 'stats.prize.value',      'Stat — Prix valeur',         '5M'),
('hackathon', 'stats.prize.label',      'Stat — Prix label',          'FCFA Prix'),
('hackathon', 'cta.button',             'Bouton CTA principal',       'Participer maintenant');

-- ============================================================
-- SOLUTIONS (table solutions + tables liées)
-- ============================================================

-- ---- 1. SoluMed ----
INSERT INTO `solutions`
  (`slug`,`name`,`tagline`,`category`,`accent_color`,`accent_color_light`,
   `hero_image`,`price`,`short_description`,`full_description`,
   `brochure_url`,`demo_url`,
   `stat1_value`,`stat1_label`,`stat2_value`,`stat2_label`,`stat3_value`,`stat3_label`,
   `is_active`,`sort_order`)
VALUES
  ('solumed','SoluMed','La solution complète pour les établissements de santé',
   'Santé','#16a34a','#dcfce7',
   'https://images.unsplash.com/photo-1584982751601-97dcc096659c?q=80&w=1600&auto=format&fit=crop',
   '650 000 FCFA',
   'SoluMed révolutionne la gestion des cliniques, hôpitaux et cabinets médicaux avec une plateforme intégrée, intuitive et pensée pour l\'Afrique.',
   'SoluMed est un logiciel de gestion médicale développé par Access Informatique pour répondre aux besoins spécifiques des établissements de santé en Côte d\'Ivoire et en Afrique subsaharienne. Il couvre l\'ensemble du parcours patient, de la prise en charge à la facturation, en passant par les prescriptions et les dossiers médicaux électroniques.',
   '/docs/solumed-presentation.pdf','#',
   '500+','Établissements utilisateurs','20 ans','D\'expertise','99.9%','Disponibilité',
   1, 1);

SET @solumed_id = LAST_INSERT_ID();

INSERT INTO `solution_modules` (`solution_id`,`title`,`description`,`sort_order`) VALUES
  (@solumed_id,'Dossier patient électronique','Centralisez toutes les informations médicales de vos patients en un seul endroit sécurisé.',1),
  (@solumed_id,'Gestion des rendez-vous','Planifiez et optimisez les consultations avec un agenda intelligent et des rappels automatiques.',2),
  (@solumed_id,'Facturation & caisse','Automatisez la facturation, les remboursements et les règlements en temps réel.',3),
  (@solumed_id,'Pharmacie intégrée','Gérez les stocks de médicaments, les ordonnances et les prescriptions médicales.',4),
  (@solumed_id,'Statistiques & reporting','Visualisez les performances de votre établissement avec des tableaux de bord clairs.',5),
  (@solumed_id,'Sécurité des données','Protégez les données sensibles de vos patients avec un chiffrement de niveau médical.',6);

INSERT INTO `solution_advantages` (`solution_id`,`text`,`sort_order`) VALUES
  (@solumed_id,'Interface pensée pour les praticiens africains',1),
  (@solumed_id,'Fonctionne en mode hors-ligne',2),
  (@solumed_id,'Compatible avec les assurances maladie locales',3),
  (@solumed_id,'Formation et support technique inclus',4);

INSERT INTO `solution_tags` (`solution_id`,`tag`,`sort_order`) VALUES
  (@solumed_id,'Dossier patient',1),
  (@solumed_id,'Rendez-vous',2),
  (@solumed_id,'Facturation',3),
  (@solumed_id,'Pharmacie',4),
  (@solumed_id,'Statistiques',5);

-- ---- 2. MySchool ----
INSERT INTO `solutions`
  (`slug`,`name`,`tagline`,`category`,`accent_color`,`accent_color_light`,
   `hero_image`,`price`,`short_description`,`full_description`,
   `brochure_url`,`demo_url`,
   `stat1_value`,`stat1_label`,`stat2_value`,`stat2_label`,`stat3_value`,`stat3_label`,
   `is_active`,`sort_order`)
VALUES
  ('myschool','MySchool','La gestion scolaire simplifiée et digitalisée',
   'Éducation','#2563eb','#dbeafe',
   'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1600&auto=format&fit=crop',
   '430 000 FCFA',
   'MySchool transforme la gestion des établissements scolaires avec une solution tout-en-un couvrant l\'administratif, le pédagogique et la communication parents.',
   'MySchool est la solution de référence pour la gestion des écoles primaires, collèges, lycées et universités. Elle permet aux directeurs, enseignants et parents de collaborer efficacement au service de la réussite des élèves, en accord avec le système éducatif ivoirien.',
   '/docs/myschool-presentation.pdf','#',
   '300+','Établissements scolaires','50 000+','Élèves gérés','15 ans','D\'expertise',
   1, 2);

SET @myschool_id = LAST_INSERT_ID();

INSERT INTO `solution_modules` (`solution_id`,`title`,`description`,`sort_order`) VALUES
  (@myschool_id,'Gestion des inscriptions','Simplifiez les inscriptions, réinscriptions et le suivi des dossiers élèves.',1),
  (@myschool_id,'Notes & bulletins','Saisissez les notes et générez les bulletins officiels en quelques clics.',2),
  (@myschool_id,'Emplois du temps','Créez et gérez les plannings de cours et les affectations des enseignants automatiquement.',3),
  (@myschool_id,'Gestion des absences','Suivez les présences, retards et alertez les parents en temps réel.',4),
  (@myschool_id,'Communication parents','Envoyez des notifications, relevés et rapports directement aux familles.',5),
  (@myschool_id,'Comptabilité scolaire','Gérez les frais de scolarité, les paiements échelonnés et la caisse de l\'établissement.',6);

INSERT INTO `solution_advantages` (`solution_id`,`text`,`sort_order`) VALUES
  (@myschool_id,'Adapté au système éducatif ivoirien',1),
  (@myschool_id,'Portail parent inclus sans frais supplémentaires',2),
  (@myschool_id,'Génération automatique des bulletins officiels',3),
  (@myschool_id,'Gestion multi-niveaux et multi-cycles',4);

INSERT INTO `solution_tags` (`solution_id`,`tag`,`sort_order`) VALUES
  (@myschool_id,'Inscriptions',1),
  (@myschool_id,'Notes',2),
  (@myschool_id,'Emplois du temps',3),
  (@myschool_id,'Absences',4),
  (@myschool_id,'Communication',5);

-- ---- 3. Matrix ----
INSERT INTO `solutions`
  (`slug`,`name`,`tagline`,`category`,`accent_color`,`accent_color_light`,
   `hero_image`,`price`,`short_description`,`full_description`,
   `brochure_url`,`demo_url`,
   `stat1_value`,`stat1_label`,`stat2_value`,`stat2_label`,`stat3_value`,`stat3_label`,
   `is_active`,`sort_order`)
VALUES
  ('matrix','Matrix','La comptabilité professionnelle à portée de clic',
   'Comptabilité','#7c3aed','#ede9fe',
   'https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&w=1200&q=80',
   '520 000 FCFA',
   'Matrix est un logiciel de comptabilité et de gestion financière adapté aux normes OHADA et aux réalités fiscales des entreprises africaines.',
   'Matrix offre une solution comptable complète et conforme aux normes OHADA, permettant aux entreprises de toutes tailles de gérer leur comptabilité générale, leurs déclarations fiscales et leurs états financiers avec précision et efficacité. Un outil de référence pour les cabinets comptables et les directions financières.',
   '/docs/matrix-presentation.pdf','#',
   '800+','Entreprises clientes','OHADA','Conformité garantie','20 ans','D\'expertise',
   1, 3);

SET @matrix_id = LAST_INSERT_ID();

INSERT INTO `solution_modules` (`solution_id`,`title`,`description`,`sort_order`) VALUES
  (@matrix_id,'Comptabilité générale','Saisie, lettrage, balance comptable et grand livre en temps réel.',1),
  (@matrix_id,'Gestion de la trésorerie','Suivez vos flux de trésorerie, rapprochements bancaires et prévisions.',2),
  (@matrix_id,'Déclarations fiscales','Générez automatiquement vos déclarations DGI, TVA et autres taxes locales.',3),
  (@matrix_id,'États financiers OHADA','Bilan, compte de résultat, TAFIRE et annexes aux normes OHADA.',4),
  (@matrix_id,'Gestion des immobilisations','Inventoriez, amortissez et suivez votre patrimoine matériel et immatériel.',5),
  (@matrix_id,'Multi-sociétés','Gérez plusieurs entités et consolidez les comptes depuis une interface unique.',6);

INSERT INTO `solution_advantages` (`solution_id`,`text`,`sort_order`) VALUES
  (@matrix_id,'Conforme aux normes OHADA et au SYSCOHADA révisé',1),
  (@matrix_id,'Compatible avec les exigences de la DGI Côte d\'Ivoire',2),
  (@matrix_id,'Gestion multi-devises intégrée',3),
  (@matrix_id,'Export vers Excel, PDF et autres formats',4);

INSERT INTO `solution_tags` (`solution_id`,`tag`,`sort_order`) VALUES
  (@matrix_id,'Comptabilité',1),
  (@matrix_id,'OHADA',2),
  (@matrix_id,'Fiscalité',3),
  (@matrix_id,'Trésorerie',4),
  (@matrix_id,'Multi-sociétés',5);

-- ---- 4. Simba ----
INSERT INTO `solutions`
  (`slug`,`name`,`tagline`,`category`,`accent_color`,`accent_color_light`,
   `hero_image`,`price`,`short_description`,`full_description`,
   `brochure_url`,`demo_url`,
   `stat1_value`,`stat1_label`,`stat2_value`,`stat2_label`,`stat3_value`,`stat3_label`,
   `is_active`,`sort_order`)
VALUES
  ('simba','Simba','La gestion immobilière de A à Z',
   'Immobilier','#ea580c','#ffedd5',
   'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
   '520 000 FCFA',
   'Simba est la solution idéale pour les agences immobilières, promoteurs et gestionnaires de patrimoine qui veulent maîtriser leur activité.',
   'Simba couvre l\'ensemble des besoins des professionnels de l\'immobilier : gestion du portefeuille de biens, suivi des contrats de location et de vente, quittancement automatisé, comptabilité immobilière et relation client. Une plateforme pensée pour le marché immobilier africain.',
   '/docs/simba-presentation.pdf','#',
   '200+','Agences partenaires','10 000+','Biens gérés','15 ans','D\'expertise',
   1, 4);

SET @simba_id = LAST_INSERT_ID();

INSERT INTO `solution_modules` (`solution_id`,`title`,`description`,`sort_order`) VALUES
  (@simba_id,'Gestion des biens','Cataloguez, classifiez et gérez tous vos biens immobiliers en location ou en vente.',1),
  (@simba_id,'Contrats & baux','Rédigez, suivez et archivez les contrats de location et de vente.',2),
  (@simba_id,'Quittancement automatisé','Générez et envoyez automatiquement les appels de loyers et quittances.',3),
  (@simba_id,'Suivi des impayés','Identifiez, relancez et gérez les loyers impayés avec des alertes automatiques.',4),
  (@simba_id,'CRM immobilier','Gérez vos prospects, clients, mandats et historiques de transactions.',5),
  (@simba_id,'Comptabilité immobilière','Charges, répartitions locatives, états comptables et reporting propriétaires.',6);

INSERT INTO `solution_advantages` (`solution_id`,`text`,`sort_order`) VALUES
  (@simba_id,'Adapté au marché immobilier africain et ivoirien',1),
  (@simba_id,'Génération automatique des quittances et reçus',2),
  (@simba_id,'Alertes de renouvellement de bail',3),
  (@simba_id,'Reporting financier détaillé pour les propriétaires',4);

INSERT INTO `solution_tags` (`solution_id`,`tag`,`sort_order`) VALUES
  (@simba_id,'Biens',1),
  (@simba_id,'Baux',2),
  (@simba_id,'Quittancement',3),
  (@simba_id,'CRM',4),
  (@simba_id,'Comptabilité',5);

-- ---- 5. SmartRH & Paie ----
INSERT INTO `solutions`
  (`slug`,`name`,`tagline`,`category`,`accent_color`,`accent_color_light`,
   `hero_image`,`price`,`short_description`,`full_description`,
   `brochure_url`,`demo_url`,
   `stat1_value`,`stat1_label`,`stat2_value`,`stat2_label`,`stat3_value`,`stat3_label`,
   `is_active`,`sort_order`)
VALUES
  ('smartrhpaie','SmartRH & Paie','La gestion RH et paie adaptée à votre entreprise',
   'Ressources Humaines','#db2777','#fce7f3',
   'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1200&q=80',
   '560 000 FCFA',
   'SmartRH automatise la gestion des ressources humaines et du bulletin de paie, en conformité totale avec le Code du Travail ivoirien et la CNPS.',
   'SmartRH & Paie est une solution RH complète qui couvre le cycle de vie du collaborateur de A à Z : recrutement, gestion administrative, calcul de la paie, gestion des congés, suivi des formations et procédures de départ. Conforme au Code du Travail ivoirien et aux règles de cotisation de la CNPS.',
   '/docs/smartrh-presentation.pdf','#',
   '400+','Entreprises clientes','CNPS','Conformité garantie','20 ans','D\'expertise',
   1, 5);

SET @smartrh_id = LAST_INSERT_ID();

INSERT INTO `solution_modules` (`solution_id`,`title`,`description`,`sort_order`) VALUES
  (@smartrh_id,'Gestion du personnel','Dossiers employés complets, contrats, avenants, historique et documents RH.',1),
  (@smartrh_id,'Paie automatisée','Calcul automatique et précis des bulletins de paie conformes CNPS et DGI.',2),
  (@smartrh_id,'Congés & absences','Gestion des demandes, validations hiérarchiques et soldes de congés en temps réel.',3),
  (@smartrh_id,'Recrutement','Suivi des candidatures, intégration et onboarding des nouveaux collaborateurs.',4),
  (@smartrh_id,'Déclarations sociales','Génération automatique des états CNPS, DIPE et autres obligations sociales.',5),
  (@smartrh_id,'Tableau de bord RH','KPIs, effectifs, turnover, pyramide des âges et masse salariale en temps réel.',6);

INSERT INTO `solution_advantages` (`solution_id`,`text`,`sort_order`) VALUES
  (@smartrh_id,'Conforme au Code du Travail de Côte d\'Ivoire',1),
  (@smartrh_id,'Déclarations CNPS générées automatiquement',2),
  (@smartrh_id,'Gestion multi-sites et multi-établissements',3),
  (@smartrh_id,'Bulletins de paie exportables en PDF',4);

INSERT INTO `solution_tags` (`solution_id`,`tag`,`sort_order`) VALUES
  (@smartrh_id,'Personnel',1),
  (@smartrh_id,'Paie',2),
  (@smartrh_id,'Congés',3),
  (@smartrh_id,'CNPS',4),
  (@smartrh_id,'Recrutement',5);

-- ---- 6. Musa ----
INSERT INTO `solutions`
  (`slug`,`name`,`tagline`,`category`,`accent_color`,`accent_color_light`,
   `hero_image`,`price`,`short_description`,`full_description`,
   `brochure_url`,`demo_url`,
   `stat1_value`,`stat1_label`,`stat2_value`,`stat2_label`,`stat3_value`,`stat3_label`,
   `is_active`,`sort_order`)
VALUES
  ('musa','Musa','La gestion municipale et des collectivités',
   'Collectivités','#0891b2','#cffafe',
   'https://images.unsplash.com/photo-1485217988980-11786ced9454?auto=format&fit=crop&w=1200&q=80',
   '480 000 FCFA',
   'Musa digitalise la gestion des communes, mairies et collectivités locales pour une administration plus efficace, transparente et moderne.',
   'Musa est une solution dédiée aux administrations locales africaines. Elle permet la gestion des taxes locales, des actes d\'état civil, du patrimoine communal et des services aux citoyens dans un environnement numérique sécurisé et conforme aux réglementations en vigueur.',
   '/docs/musa-presentation.pdf','#',
   '50+','Communes équipées','1M+','Citoyens servis','10 ans','D\'expertise',
   1, 6);

SET @musa_id = LAST_INSERT_ID();

INSERT INTO `solution_modules` (`solution_id`,`title`,`description`,`sort_order`) VALUES
  (@musa_id,'État civil numérique','Gestion des naissances, mariages, décès, jugements supplétifs et actes officiels.',1),
  (@musa_id,'Gestion des taxes','Taxation locale, recouvrement des recettes et édition de quittances numériques.',2),
  (@musa_id,'Patrimoine communal','Inventaire, suivi et valorisation du patrimoine de la commune.',3),
  (@musa_id,'Gestion du personnel','RH et paie du personnel communal conforme à la réglementation.',4),
  (@musa_id,'Budget & comptabilité','Budget prévisionnel, exécution budgétaire et états financiers de la commune.',5),
  (@musa_id,'Portail citoyen','Demandes en ligne, prise de rendez-vous et suivi des dossiers administratifs.',6);

INSERT INTO `solution_advantages` (`solution_id`,`text`,`sort_order`) VALUES
  (@musa_id,'Conforme aux réglementations des collectivités africaines',1),
  (@musa_id,'Interopérable avec les systèmes nationaux',2),
  (@musa_id,'Formation des agents fonctionnaires incluse',3),
  (@musa_id,'Support dédié aux administrations publiques',4);

INSERT INTO `solution_tags` (`solution_id`,`tag`,`sort_order`) VALUES
  (@musa_id,'État civil',1),
  (@musa_id,'Taxes',2),
  (@musa_id,'Patrimoine',3),
  (@musa_id,'Budget',4),
  (@musa_id,'Portail citoyen',5);

-- ============================================================
-- FORMATIONS (table formations + tables liées)
-- Prix en FCFA d'après le formulaire d'inscription du site
-- ============================================================

-- ---- 1. Développement Web Full Stack ----
INSERT INTO `formations`
  (`slug`,`title`,`category`,`duration`,`level`,`price`,`description`,`image_url`,`is_active`,`sort_order`)
VALUES
  ('developpement-web-full-stack',
   'Développement Web Full Stack',
   'Développement','6 mois','Débutant à intermédiaire','150 000 FCFA',
   'Apprenez HTML, CSS, JavaScript, Vue.js, Node.js et les bases de données avec des projets concrets.',
   'https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=1200&auto=format&fit=crop',
   1, 1);

SET @f1_id = LAST_INSERT_ID();

INSERT INTO `formation_skills` (`formation_id`,`skill`,`sort_order`) VALUES
  (@f1_id,'HTML',1),(@f1_id,'Vue.js',2),(@f1_id,'Tailwind',3),(@f1_id,'API',4);

INSERT INTO `formation_modules` (`formation_id`,`title`,`description`,`duration`,`sort_order`) VALUES
  (@f1_id,'Fondations Web','HTML, CSS et responsive design pour créer des interfaces modernes.','3 semaines',1),
  (@f1_id,'JavaScript & Frameworks','JavaScript moderne, Vue.js et gestion d\'état pour des applications interactives.','4 semaines',2),
  (@f1_id,'Backend et API','Node.js, Express et connexions aux bases de données.','3 semaines',3),
  (@f1_id,'Projet pratique','Développement d\'une application complète avec frontend et backend.','4 semaines',4);

INSERT INTO `formation_benefits` (`formation_id`,`text`,`sort_order`) VALUES
  (@f1_id,'Portfolio prêt à présenter',1),
  (@f1_id,'Support technique hebdomadaire',2),
  (@f1_id,'Certificat de formation',3);

INSERT INTO `formation_outcomes` (`formation_id`,`text`,`sort_order`) VALUES
  (@f1_id,'Créer un site web complet',1),
  (@f1_id,'Déployer un backend Node.js',2),
  (@f1_id,'Maîtriser les outils modernes du web',3);

-- ---- 2. Bureautique Professionnelle ----
INSERT INTO `formations`
  (`slug`,`title`,`category`,`duration`,`level`,`price`,`description`,`image_url`,`is_active`,`sort_order`)
VALUES
  ('bureautique-professionnelle',
   'Bureautique Professionnelle',
   'Productivité','2 mois','Débutant','60 000 FCFA',
   'Maîtrisez Word, Excel, PowerPoint et les outils collaboratifs modernes.',
   'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=1200&auto=format&fit=crop',
   1, 2);

SET @f2_id = LAST_INSERT_ID();

INSERT INTO `formation_skills` (`formation_id`,`skill`,`sort_order`) VALUES
  (@f2_id,'Word',1),(@f2_id,'Excel',2),(@f2_id,'PowerPoint',3),(@f2_id,'Google Workspace',4);

INSERT INTO `formation_modules` (`formation_id`,`title`,`description`,`duration`,`sort_order`) VALUES
  (@f2_id,'Traitement de texte avancé','Création de documents professionnels et automatisation avec Word.','2 semaines',1),
  (@f2_id,'Tableurs efficaces','Fonctions, tableaux croisés dynamiques et visualisation avec Excel.','3 semaines',2),
  (@f2_id,'Présentations impactantes','Slides clairs et attractifs avec PowerPoint.','2 semaines',3),
  (@f2_id,'Outils collaboratifs','Travail d\'équipe avec Google Workspace et gestion de projet.','2 semaines',4);

INSERT INTO `formation_benefits` (`formation_id`,`text`,`sort_order`) VALUES
  (@f2_id,'Gain de productivité immédiat',1),
  (@f2_id,'Modèles professionnels',2),
  (@f2_id,'Astuces pour collaborer efficacement',3);

INSERT INTO `formation_outcomes` (`formation_id`,`text`,`sort_order`) VALUES
  (@f2_id,'Créer des documents professionnels',1),
  (@f2_id,'Automatiser des tâches bureautiques',2),
  (@f2_id,'Piloter des projets collaboratifs',3);

-- ---- 3. Design Graphique & UI/UX ----
INSERT INTO `formations`
  (`slug`,`title`,`category`,`duration`,`level`,`price`,`description`,`image_url`,`is_active`,`sort_order`)
VALUES
  ('design-graphique-ui-ux',
   'Design Graphique & UI/UX',
   'Design','4 mois','Débutant à avancé','120 000 FCFA',
   'Créez des interfaces modernes avec Figma, Photoshop et les principes UX/UI.',
   'https://images.unsplash.com/photo-1558655146-9f40138edfeb?q=80&w=1200&auto=format&fit=crop',
   1, 3);

SET @f3_id = LAST_INSERT_ID();

INSERT INTO `formation_skills` (`formation_id`,`skill`,`sort_order`) VALUES
  (@f3_id,'Figma',1),(@f3_id,'Photoshop',2),(@f3_id,'UI Design',3),(@f3_id,'UX',4);

INSERT INTO `formation_modules` (`formation_id`,`title`,`description`,`duration`,`sort_order`) VALUES
  (@f3_id,'Principes UX','Comprendre l\'expérience utilisateur et les besoins des utilisateurs.','3 semaines',1),
  (@f3_id,'Design d\'interface','Création de maquettes et prototypes sur Figma.','4 semaines',2),
  (@f3_id,'Design graphique','Visuels, logos et image de marque avec Photoshop.','3 semaines',3),
  (@f3_id,'Portfolio professionnel','Compilation de projets pour mettre en valeur vos compétences.','4 semaines',4);

INSERT INTO `formation_benefits` (`formation_id`,`text`,`sort_order`) VALUES
  (@f3_id,'Design centré utilisateur',1),
  (@f3_id,'Validation par tests utilisateurs',2),
  (@f3_id,'Création d\'un portfolio complet',3);

INSERT INTO `formation_outcomes` (`formation_id`,`text`,`sort_order`) VALUES
  (@f3_id,'Prototyper des interfaces modernes',1),
  (@f3_id,'Créer une identité visuelle forte',2),
  (@f3_id,'Lancer un portfolio professionnel',3);

-- ---- 4. Cybersécurité & Réseaux ----
INSERT INTO `formations`
  (`slug`,`title`,`category`,`duration`,`level`,`price`,`description`,`image_url`,`is_active`,`sort_order`)
VALUES
  ('cybersecurite-reseaux',
   'Cybersécurité & Réseaux',
   'Sécurité','5 mois','Intermédiaire','180 000 FCFA',
   'Découvrez les fondamentaux de la sécurité informatique et de la protection des réseaux.',
   'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200&auto=format&fit=crop',
   1, 4);

SET @f4_id = LAST_INSERT_ID();

INSERT INTO `formation_skills` (`formation_id`,`skill`,`sort_order`) VALUES
  (@f4_id,'Sécurité',1),(@f4_id,'Linux',2),(@f4_id,'Réseaux',3),(@f4_id,'Firewall',4);

INSERT INTO `formation_modules` (`formation_id`,`title`,`description`,`duration`,`sort_order`) VALUES
  (@f4_id,'Réseaux et architecture','Topologies, protocoles et conception de réseaux sécurisés.','4 semaines',1),
  (@f4_id,'Sécurité système','Administration Linux et sécurisation des serveurs.','4 semaines',2),
  (@f4_id,'Protection et défense','Firewall, chiffrement et gestion des accès.','4 semaines',3),
  (@f4_id,'Tests d\'intrusion','Analyse de vulnérabilités et réponses aux incidents.','4 semaines',4);

INSERT INTO `formation_benefits` (`formation_id`,`text`,`sort_order`) VALUES
  (@f4_id,'Simulations réelles de sécurité',1),
  (@f4_id,'Compétences opérationnelles',2),
  (@f4_id,'Préparation aux certifications',3);

INSERT INTO `formation_outcomes` (`formation_id`,`text`,`sort_order`) VALUES
  (@f4_id,'Sécuriser un réseau d\'entreprise',1),
  (@f4_id,'Réagir aux incidents de sécurité',2),
  (@f4_id,'Mettre en place des protections efficaces',3);

-- ---- 5. Intelligence Artificielle ----
INSERT INTO `formations`
  (`slug`,`title`,`category`,`duration`,`level`,`price`,`description`,`image_url`,`is_active`,`sort_order`)
VALUES
  ('intelligence-artificielle',
   'Intelligence Artificielle',
   'IA','4 mois','Intermédiaire','200 000 FCFA',
   'Initiez-vous au Machine Learning, aux IA génératives et aux outils d\'automatisation modernes.',
   'https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=1200&auto=format&fit=crop',
   1, 5);

SET @f5_id = LAST_INSERT_ID();

INSERT INTO `formation_skills` (`formation_id`,`skill`,`sort_order`) VALUES
  (@f5_id,'Python',1),(@f5_id,'Machine Learning',2),(@f5_id,'ChatGPT',3),(@f5_id,'Data',4);

INSERT INTO `formation_modules` (`formation_id`,`title`,`description`,`duration`,`sort_order`) VALUES
  (@f5_id,'Python pour l\'IA','Bases de Python et manipulation de données.','3 semaines',1),
  (@f5_id,'Machine Learning','Modèles supervisés et non supervisés.','4 semaines',2),
  (@f5_id,'IA générative','Création de prompts et intégration de modèles génératifs.','3 semaines',3),
  (@f5_id,'Projet IA','Développement d\'une application intelligente fonctionnelle.','4 semaines',4);

INSERT INTO `formation_benefits` (`formation_id`,`text`,`sort_order`) VALUES
  (@f5_id,'Compétences en IA appliquée',1),
  (@f5_id,'Utilisation de modèles modernes',2),
  (@f5_id,'Projet pratique inclus',3);

INSERT INTO `formation_outcomes` (`formation_id`,`text`,`sort_order`) VALUES
  (@f5_id,'Construire un modèle de machine learning',1),
  (@f5_id,'Déployer une application intelligente',2),
  (@f5_id,'Utiliser les outils IA du marché',3);

-- ---- 6. Marketing Digital ----
INSERT INTO `formations`
  (`slug`,`title`,`category`,`duration`,`level`,`price`,`description`,`image_url`,`is_active`,`sort_order`)
VALUES
  ('marketing-digital',
   'Marketing Digital',
   'Business','3 mois','Débutant','80 000 FCFA',
   'Développez votre visibilité avec les réseaux sociaux, la publicité et le branding.',
   'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=1200&auto=format&fit=crop',
   1, 6);

SET @f6_id = LAST_INSERT_ID();

INSERT INTO `formation_skills` (`formation_id`,`skill`,`sort_order`) VALUES
  (@f6_id,'Facebook Ads',1),(@f6_id,'SEO',2),(@f6_id,'Canva',3),(@f6_id,'Branding',4);

INSERT INTO `formation_modules` (`formation_id`,`title`,`description`,`duration`,`sort_order`) VALUES
  (@f6_id,'Stratégie digitale','Analyse du marché, persona et positionnement.','3 semaines',1),
  (@f6_id,'Réseaux sociaux','Création de contenus et gestion de campagnes.','3 semaines',2),
  (@f6_id,'Publicité en ligne','Campagnes Ads, ciblage et optimisation.','3 semaines',3),
  (@f6_id,'Performance','Analytics, SEO et conversion.','3 semaines',4);

INSERT INTO `formation_benefits` (`formation_id`,`text`,`sort_order`) VALUES
  (@f6_id,'Plan marketing prêt à l\'emploi',1),
  (@f6_id,'Campagnes optimisées',2),
  (@f6_id,'Suivi des performances',3);

INSERT INTO `formation_outcomes` (`formation_id`,`text`,`sort_order`) VALUES
  (@f6_id,'Lancer une campagne digitale performante',1),
  (@f6_id,'Optimiser le trafic et les conversions',2),
  (@f6_id,'Mesurer le ROI avec des outils modernes',3);

-- ============================================================
-- PARTENAIRES / RÉFÉRENCES (table partners)
-- Logos affichés sur Accueil et À propos
-- ============================================================
INSERT INTO `partners` (`name`,`logo_url`,`is_active`,`sort_order`) VALUES
  ('Centre Médical Lumière', '/src/assets/images/References/logrefcentremedicalelumiere.png', 1, 1),
  ('YNACASS',                '/src/assets/images/References/logo_synacassci.png',             1, 2),
  ('FPPN',                   '/src/assets/images/References/logrefFPPN.png',                  1, 3),
  ('EPSI',                   '/src/assets/images/References/logrefepsi.png',                  1, 4),
  ('CMAC',                   '/src/assets/images/References/logrefCliniqMedicalAcasa.png',    1, 5),
  ('IRMA',                   '/src/assets/images/References/logrefirmabassam.jpeg',           1, 6),
  ('MUSACNRA',               '/src/assets/images/References/logrefMUSACNRA.png',              1, 7),
  ('TANCHIVOIRE',            '/src/assets/images/References/logreftranchIvoire.png',          1, 8),
  ('Université Atlantique',  '/src/assets/images/References/logrefuniversiteatlantiq.jfif',   1, 9),
  ('Clinique Sainte Henriette','/src/assets/images/References/logrefpolycliqstHenriette.jpg', 1, 10);

-- ============================================================
-- HACKATHON — THÈMES (table hackathon_themes)
-- ============================================================
INSERT INTO `hackathon_themes` (`icon`,`title`,`description`,`sort_order`,`is_active`) VALUES
  ('🤖','Intelligence Artificielle','Créez des solutions IA innovantes pour automatiser et transformer les usages.',1,1),
  ('🏥','Santé numérique','Développez des outils digitaux pour améliorer les soins et la gestion médicale.',2,1),
  ('🎓','Éducation digitale','Imaginez les plateformes éducatives et outils pédagogiques du futur.',3,1),
  ('🛡️','Cybersécurité','Construisez des solutions pour protéger les données et systèmes numériques.',4,1),
  ('🏙️','Smart City','Réinventez les villes intelligentes grâce aux nouvelles technologies.',5,1),
  ('💳','Fintech','Concevez des solutions financières modernes adaptées à l\'Afrique.',6,1);

-- ============================================================
-- HACKATHON — PROGRAMME (table hackathon_timeline)
-- ============================================================
INSERT INTO `hackathon_timeline` (`day_label`,`title`,`description`,`sort_order`) VALUES
  ('Jour 1','Lancement officiel','Présentation des défis, constitution des équipes et démarrage du hackathon.',1),
  ('Jour 2','Développement & mentorat','Travail intensif sur les projets avec accompagnement des experts et mentors.',2),
  ('Jour 3','Pitch final & remise des prix','Présentation des projets devant le jury et annonce des équipes gagnantes.',3);

-- ============================================================
-- HACKATHON — RÉCOMPENSES (table hackathon_rewards)
-- ============================================================
INSERT INTO `hackathon_rewards` (`emoji`,`title`,`amount`,`is_featured`,`sort_order`) VALUES
  ('🥇','1er Prix','3 000 000 FCFA',1,1),
  ('🥈','2e Prix','1 500 000 FCFA',0,2),
  ('🥉','3e Prix','500 000 FCFA',  0,3);

SET FOREIGN_KEY_CHECKS = 1;
