import { computed } from 'vue'

const normalize = (str) =>
  str
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')

const getSimilarity = (str1, str2) => {
  const s1 = normalize(str1)
  const s2 = normalize(str2)

  if (s1 === s2) return 1
  if (s1.includes(s2) || s2.includes(s1)) return 0.8

  const words1 = s1.split(' ')
  const words2 = s2.split(' ')

  for (const w1 of words1) {
    for (const w2 of words2) {
      if (w1 === w2 || w1.includes(w2) || w2.includes(w1)) {
        if (w1.length > 3 || w2.length > 3) return 0.7
      }
    }
  }

  if (s1.length > 2 && s2.length > 2) {
    let differences = 0
    const minLen = Math.min(s1.length, s2.length)
    for (let i = 0; i < minLen; i++) {
      if (s1[i] !== s2[i]) differences++
    }
    differences += Math.abs(s1.length - s2.length)
    if (differences <= 2) return 0.6
  }

  return 0
}

export function useFormationSearch(formations, searchQuery) {
  const isExactMatch = computed(() => {
    if (!searchQuery.value.trim()) return false
    const query = normalize(searchQuery.value)
    return formations.some((f) => normalize(f.title) === query)
  })

  const filteredFormations = computed(() => {
    if (!searchQuery.value.trim()) return formations

    const query = normalize(searchQuery.value)
    return formations.filter((formation) => {
      const title = normalize(formation.title)
      const category = normalize(formation.category)
      const skills = formation.skills.some((skill) => normalize(skill).includes(query))
      return title.includes(query) || category.includes(query) || skills
    })
  })

  const suggestion = computed(() => {
    if (!searchQuery.value.trim()) return null
    if (filteredFormations.value.length > 0) return null

    const query = normalize(searchQuery.value)
    let bestMatch = null
    let bestScore = 0

    for (const formation of formations) {
      const score = getSimilarity(query, normalize(formation.title))
      if (score > bestScore && score > 0.35) {
        bestScore = score
        bestMatch = formation.title
      }
    }

    return bestMatch
  })

  const applySuggestion = () => {
    if (suggestion.value) {
      searchQuery.value = suggestion.value
    }
  }

  const clearSearch = () => {
    searchQuery.value = ''
  }

  return {
    isExactMatch,
    filteredFormations,
    suggestion,
    applySuggestion,
    clearSearch,
  }
}
