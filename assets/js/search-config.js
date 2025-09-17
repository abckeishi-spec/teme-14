/**
 * Search System Configuration
 * Version: 4.0.0
 * Updated: 2025-09-17
 */
const GISearchConfig = {
    version: '4.0.0',
    phase: 'Production',
    timeout: 30000,
    
    elements: {
        searchInputs: ['gi-search-input-unified-main'],
        filters: {
            amount: ['amount-filter'],
            status: ['status-filter'], 
            industry: ['industry-filter'],
            region: ['region-filter']
        },
        sortSelect: 'sort-order',
        perPageSelect: 'per-page-select',
        resultsContainer: 'search-results-unified',
        pagination: 'pagination-unified',
        loadingIndicator: 'search-loading'
    },
    
    ui: {
        showLoadingOnSearch: true,
        enableAutoComplete: true,
        debounceDelay: 300,
        animationDuration: 200
    },
    
    api: {
        endpoint: 'gi_unified_search',
        suggest: 'gi_search_suggest',
        favorites: 'gi_toggle_favorite'
    }
};

// Export for global access
window.GISearchConfig = GISearchConfig;