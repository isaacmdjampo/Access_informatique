const slugify = (text) =>
  text
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-|-$/g, '')

export const formations = [
  {
    id: 1,
    slug: slugify('Développement Web Full Stack'),
    title: 'Développement Web Full Stack',
    category: 'Développement',
    image:
      'https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=1200&auto=format&fit=crop',
    description:
      'Apprenez HTML, CSS, JavaScript, Vue.js, Node.js et les bases de données avec des projets concrets.',
    skills: ['HTML', 'Vue.js', 'Tailwind', 'API'],
    duration: '6 mois',
    level: 'Débutant à intermédiaire',
    price: '€1 200',
    modules: [
      {
        title: 'Fondations Web',
        description: 'HTML, CSS et responsive design pour créer des interfaces modernes.',
        duration: '3 semaines',
      },
      {
        title: 'JavaScript & Frameworks',
        description:
          'JavaScript moderne, Vue.js et gestion d’état pour des applications interactives.',
        duration: '4 semaines',
      },
      {
        title: 'Backend et API',
        description: 'Node.js, Express et connexions aux bases de données.',
        duration: '3 semaines',
      },
      {
        title: 'Projet pratique',
        description: 'Développement d’une application complète avec frontend et backend.',
        duration: '4 semaines',
      },
    ],
    benefits: [
      'Portfolio prêt à présenter',
      'Support technique hebdomadaire',
      'Certificat de formation',
    ],
    outcomes: [
      'Créer un site web complet',
      'Déployer un backend Node.js',
      'Maîtriser les outils modernes du web',
    ],
  },
  {
    id: 2,
    slug: slugify('Bureautique Professionnelle'),
    title: 'Bureautique Professionnelle',
    category: 'Productivité',
    image:
      'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=1200&auto=format&fit=crop',
    description: 'Maîtrisez Word, Excel, PowerPoint et les outils collaboratifs modernes.',
    skills: ['Word', 'Excel', 'PowerPoint', 'Google Workspace'],
    duration: '2 mois',
    level: 'Débutant',
    price: '€290',
    modules: [
      {
        title: 'Traitement de texte avancé',
        description: 'Création de documents professionnels et automatisation avec Word.',
        duration: '2 semaines',
      },
      {
        title: 'Tableurs efficaces',
        description: 'Fonctions, tableaux croisés dynamiques et visualisation avec Excel.',
        duration: '3 semaines',
      },
      {
        title: 'Présentations impactantes',
        description: 'Slides clairs et attractifs avec PowerPoint.',
        duration: '2 semaines',
      },
      {
        title: 'Outils collaboratifs',
        description: 'Travail d’équipe avec Google Workspace et gestion de projet.',
        duration: '2 semaines',
      },
    ],
    benefits: [
      'Gain de productivité immédiat',
      'Modèles professionnels',
      'Astuces pour collaborer efficacement',
    ],
    outcomes: [
      'Créer des documents professionnels',
      'Automatiser des tâches bureautiques',
      'Piloter des projets collaboratifs',
    ],
  },
  {
    id: 3,
    slug: slugify('Design Graphique & UI/UX'),
    title: 'Design Graphique & UI/UX',
    category: 'Design',
    image:
      'https://images.unsplash.com/photo-1558655146-9f40138edfeb?q=80&w=1200&auto=format&fit=crop',
    description: 'Créez des interfaces modernes avec Figma, Photoshop et les principes UX/UI.',
    skills: ['Figma', 'Photoshop', 'UI Design', 'UX'],
    duration: '4 mois',
    level: 'Débutant à avancé',
    price: '€950',
    modules: [
      {
        title: 'Principes UX',
        description: 'Comprendre l’expérience utilisateur et les besoins des utilisateurs.',
        duration: '3 semaines',
      },
      {
        title: 'Design d’interface',
        description: 'Création de maquettes et prototypes sur Figma.',
        duration: '4 semaines',
      },
      {
        title: 'Design graphique',
        description: 'Visuels, logos et image de marque avec Photoshop.',
        duration: '3 semaines',
      },
      {
        title: 'Portfolio professionnel',
        description: 'Compilation de projets pour mettre en valeur vos compétences.',
        duration: '4 semaines',
      },
    ],
    benefits: [
      'Design centré utilisateur',
      'Validation par tests utilisateurs',
      'Création d’un portfolio complet',
    ],
    outcomes: [
      'Prototyper des interfaces modernes',
      'Créer une identité visuelle forte',
      'Lancer un portfolio professionnel',
    ],
  },
  {
    id: 4,
    slug: slugify('Cybersécurité & Réseaux'),
    title: 'Cybersécurité & Réseaux',
    category: 'Sécurité',
    image:
      'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200&auto=format&fit=crop',
    description:
      'Découvrez les fondamentaux de la sécurité informatique et de la protection des réseaux.',
    skills: ['Sécurité', 'Linux', 'Réseaux', 'Firewall'],
    duration: '5 mois',
    level: 'Intermédiaire',
    price: '€1 450',
    modules: [
      {
        title: 'Réseaux et architecture',
        description: 'Topologies, protocoles et conception de réseaux sécurisés.',
        duration: '4 semaines',
      },
      {
        title: 'Sécurité système',
        description: 'Administration Linux et sécurisation des serveurs.',
        duration: '4 semaines',
      },
      {
        title: 'Protection et défense',
        description: 'Firewall, chiffrement et gestion des accès.',
        duration: '4 semaines',
      },
      {
        title: 'Tests d’intrusion',
        description: 'Analyse de vulnérabilités et réponses aux incidents.',
        duration: '4 semaines',
      },
    ],
    benefits: [
      'Simulations réelles de sécurité',
      'Compétences opérationnelles',
      'Préparation aux certifications',
    ],
    outcomes: [
      'Sécuriser un réseau d’entreprise',
      'Réagir aux incidents de sécurité',
      'Mettre en place des protections efficaces',
    ],
  },
  {
    id: 5,
    slug: slugify('Intelligence Artificielle'),
    title: 'Intelligence Artificielle',
    category: 'IA',
    image:
      'https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=1200&auto=format&fit=crop',
    description:
      'Initiez-vous au Machine Learning, aux IA génératives et aux outils d’automatisation modernes.',
    skills: ['Python', 'Machine Learning', 'ChatGPT', 'Data'],
    duration: '4 mois',
    level: 'Intermédiaire',
    price: '€1 100',
    modules: [
      {
        title: 'Python pour l’IA',
        description: 'Bases de Python et manipulation de données.',
        duration: '3 semaines',
      },
      {
        title: 'Machine Learning',
        description: 'Modèles supervisés et non supervisés.',
        duration: '4 semaines',
      },
      {
        title: 'IA générative',
        description: 'Création de prompts et intégration de modèles génératifs.',
        duration: '3 semaines',
      },
      {
        title: 'Projet IA',
        description: 'Développement d’une application intelligente fonctionnelle.',
        duration: '4 semaines',
      },
    ],
    benefits: [
      'Compétences en IA appliquée',
      'Utilisation de modèles modernes',
      'Projet pratique inclus',
    ],
    outcomes: [
      'Construire un modèle de machine learning',
      'Déployer une application intelligente',
      'Utiliser les outils IA du marché',
    ],
  },
  {
    id: 6,
    slug: slugify('Marketing Digital'),
    title: 'Marketing Digital',
    category: 'Business',
    image:
      'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=1200&auto=format&fit=crop',
    description:
      'Développez votre visibilité avec les réseaux sociaux, la publicité et le branding.',
    skills: ['Facebook Ads', 'SEO', 'Canva', 'Branding'],
    duration: '3 mois',
    level: 'Débutant',
    price: '€480',
    modules: [
      {
        title: 'Stratégie digitale',
        description: 'Analyse du marché, persona et positionnement.',
        duration: '3 semaines',
      },
      {
        title: 'Réseaux sociaux',
        description: 'Création de contenus et gestion de campagnes.',
        duration: '3 semaines',
      },
      {
        title: 'Publicité en ligne',
        description: 'Campagnes Ads, ciblage et optimisation.',
        duration: '3 semaines',
      },
      {
        title: 'Performance',
        description: 'Analytics, SEO et conversion.',
        duration: '3 semaines',
      },
    ],
    benefits: ['Plan marketing prêt à l’emploi', 'Campagnes optimisées', 'Suivi des performances'],
    outcomes: [
      'Lancer une campagne digitale performante',
      'Optimiser le trafic et les conversions',
      'Mesurer le ROI avec des outils modernes',
    ],
  },
]
