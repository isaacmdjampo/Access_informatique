import { IconCode, IconShield, IconHeadset } from '@/data/icons'
import { listeReferences } from '@/data/aboutData'

export const softwareSlides = [
  {
    id: 1,
    name: 'SoluMed',
    image: '/src/assets/images/Logiciels/cvsolumed.png',
    route: '/solutions/solumed',
  },
  {
    id: 2,
    name: 'MySchool',
    image: '/src/assets/images/Logiciels/cvmyschool.png',
    route: '/solutions/myschool',
  },
  {
    id: 3,
    name: 'Matrix',
    image:
      'https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&w=1200&q=80',
    route: '/solutions/matrix',
  },
  {
    id: 4,
    name: 'SmartRH Paie',
    image: '/src/assets/images/Logiciels/cvsmartrh.png',
    route: '/solutions/smartrhpaie',
  },
  {
    id: 5,
    name: 'Simba',
    image:
      'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
    route: '/solutions/simba',
  },
  {
    id: 6,
    name: 'Musa',
    image:
      'https://images.unsplash.com/photo-1485217988980-11786ced9454?auto=format&fit=crop&w=1200&q=80',
    route: '/solutions/musa',
  },
]

export const partners = listeReferences.map((ref, index) => ({
  id: index + 1,
  name: ref.nom,
  logo: ref.logo,
  url: '#',
}))

export const pillars = [
  {
    icon: IconCode,
    title: 'Développement sur mesure',
    text: 'Chaque logiciel est conçu pour correspondre exactement à vos processus métier, sans fonctionnalités imposées inutiles.',
  },
  {
    icon: IconShield,
    title: 'Conformité locale',
    text: 'Nos solutions respectent les normes ivoiriennes (SYSCOHADA, CNPS, DGI) et sont maintenues à jour.',
  },
  {
    icon: IconHeadset,
    title: 'Support humain permanent',
    text: 'Formation incluse et assistance technique du lundi au samedi par une équipe locale.',
  },
]
