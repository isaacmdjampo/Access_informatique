import {
  IconCode,
  IconShield,
  IconUsers,
  IconHospital,
  IconSchool,
  IconChart,
  IconHome,
  IconHandshake,
  IconHeadset,
} from '@/data/icons'

export const categories = [
  { id: 'all', label: 'Tous les logiciels' },
  { id: 'sante', label: '🏥 Santé' },
  { id: 'education', label: '🎓 Éducation' },
  { id: 'compta', label: '📊 Comptabilité' },
  { id: 'rh', label: '👥 RH & Paie' },
  { id: 'immo', label: '🏠 Immobilier' },
  { id: 'asso', label: '🤝 Associations' },
]

export const solutions = [
  {
    id: 1,
    name: 'SoluMed',
    category: 'Santé',
    categoryId: 'sante',
    categoryIcon: IconHospital,
    categoryStyle:
      'background: rgba(237,249,235,0.95); color: #16a34a; border-color: rgba(34,197,94,0.25);',
    tagline: 'Gestion médicale complète',
    shortDescription:
      'Solution complète de gestion médicale avec dossier patient électronique et facturation intégrée.',
    description:
      'SoluMed est un véritable outil de gestion de votre établissement médical (Clinique, Centre de santé, Groupe Médical). Développé depuis 2006 par Access Informatique, ce logiciel vous permet de gérer les consultations, les hospitalisations, la pharmacie, la facturation et bien plus encore.',
    image: '/src/assets/images/Logiciels/cvsolumed.png',
    tags: ['Consultations', 'Hospitalisation', 'Pharmacie', 'Facturation', 'Statistiques'],
    route: '/solutions/solumed',
  },
  {
    id: 2,
    name: 'MySchool',
    category: 'Éducation',
    categoryId: 'education',
    categoryIcon: IconSchool,
    categoryStyle:
      'background: rgba(237,249,235,0.95); color: #16a34a; border-color: rgba(34,197,94,0.25);',
    tagline: 'La gestion scolaire digitale',
    shortDescription:
      'Espace collaboratif pour écoles, enseignants, parents et étudiants avec gestion des bulletins et inscriptions.',
    description:
      "MySchool est une application web et mobile développée pour les Écoles Primaires, Secondaires, Grandes Écoles et Universités. C'est un espace collaboratif pour l'école, les enseignants, les parents d'élèves et les étudiants.",
    image: '/src/assets/images/Logiciels/cvmyschool.png',
    tags: ['Bulletins', 'Inscriptions', 'Absences', 'Parents', 'Paiements'],
    route: '/solutions/myschool',
  },
  {
    id: 3,
    name: 'Matrix',
    category: 'Comptabilité',
    categoryId: 'compta',
    categoryIcon: IconChart,
    categoryStyle:
      'background: rgba(237,249,235,0.95); color: #16a34a; border-color: rgba(34,197,94,0.25);',
    tagline: 'Comptabilité SYSCOHADA',
    shortDescription:
      'Logiciel comptable conforme aux normes SYSCOHADA pour entreprises, mutuelles et ONG.',
    description:
      "Matrix est un logiciel destiné au traitement informatique de toutes les opérations comptables d'une entreprise, mutuelle ou ONG. Développé par Access Informatique, cette solution est conforme aux normes SYSCOHADA en vigueur.",
    image:
      'https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&w=1200&q=80',
    tags: ['SYSCOHADA', 'Bilan', 'Grand Livre', 'Budget', 'Déclarations'],
    route: '/solutions/matrix',
  },
  {
    id: 4,
    name: 'SmartRH Paie',
    category: 'RH & Paie',
    categoryId: 'rh',
    categoryIcon: IconUsers,
    categoryStyle:
      'background: rgba(237,249,235,0.95); color: #16a34a; border-color: rgba(34,197,94,0.25);',
    tagline: 'Capital humain & conformité',
    shortDescription:
      'Gestion complète des RH et de la paie avec conformité CNPS et traitement des bulletins.',
    description:
      'SmartRH Paie est une solution complète pour la gestion des ressources humaines et le traitement de la paie. Elle permet la gestion des contrats, des congés, des absences, des bulletins de salaire et des déclarations sociales.',
    image: '/src/assets/images/Logiciels/cvsmartrh.png',
    tags: ['Bulletins', 'Congés', 'CNPS', 'Contrats', 'Reporting RH'],
    route: '/solutions/smartrhpaie',
  },
  {
    id: 5,
    name: 'Simba',
    category: 'Immobilier',
    categoryId: 'immo',
    categoryIcon: IconHome,
    categoryStyle:
      'background: rgba(237,249,235,0.95); color: #16a34a; border-color: rgba(34,197,94,0.25);',
    tagline: 'Gestion locative simplifiée',
    shortDescription:
      'Automatisation de la gestion locative : loyers, quittances, relances et suivi des locataires.',
    description:
      "La gestion de plusieurs appartements ou maisons en location peut vite devenir complexe : régularisation des impayés, lettres de relance, quittances de loyers. Simba est la solution de gestion immobilière d'Access Informatique qui automatise et simplifie l'ensemble du cycle locatif.",
    image:
      'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
    tags: ['Loyers', 'Quittances', 'Relances', 'Locataires', 'Propriétés'],
    route: '/solutions/simba',
  },
  {
    id: 6,
    name: 'Musa',
    category: 'Associations',
    categoryId: 'asso',
    categoryIcon: IconHandshake,
    categoryStyle:
      'background: rgba(237,249,235,0.95); color: #16a34a; border-color: rgba(34,197,94,0.25);',
    tagline: 'Mutuelles & coopératives',
    shortDescription:
      'Gestion des adhérents, cotisations, prestations et fonds de solidarité pour associations.',
    description:
      'Musa est le logiciel de gestion des communautés, mutuelles, associations et coopératives développé par Access Informatique. Il permet de gérer les adhérents, les cotisations, les prestations, les fonds de solidarité et de produire des rapports financiers détaillés.',
    image:
      'https://images.unsplash.com/photo-1485217988980-11786ced9454?auto=format&fit=crop&w=1200&q=80',
    tags: ['Adhérents', 'Cotisations', 'Prestations', 'Solidarité', 'Rapports'],
    route: '/solutions/musa',
  },
]

export const whyUs = [
  {
    icon: IconCode,
    title: 'Développement sur mesure',
    text: 'Chaque logiciel est conçu pour correspondre exactement à vos processus métier. Aucun compromis, aucune fonctionnalité superflue imposée.',
  },
  {
    icon: IconShield,
    title: 'Conformité locale',
    text: 'Nos solutions respectent les normes ivoiriennes (SYSCOHADA, CNPS, DGI) et sont maintenues à jour lors de chaque évolution réglementaire.',
  },
  {
    icon: IconHeadset,
    title: 'Support humain permanent',
    text: 'Formation incluse, assistance technique disponible du lundi au samedi. Une équipe locale, proche de vous, toujours prête à intervenir.',
  },
]
