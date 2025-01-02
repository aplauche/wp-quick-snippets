document.addEventListener('alpine:init', () => {
  Alpine.data('postFilter', postFilter)
})


function postFilter() {
  return {
      loading: false,
      filters: {
          theme: [],
          workshop: [],
          audience: [],
      },
      search: '',
      results: [],
      pagination: {
          currentPage: 1,
          totalPages: 1,
      },
      init() {
          // Sync filters and fetch results on page load
          this.updateFiltersFromQueryString();
          this.fetchResults();

          // Watchers for filter and search updates
          this.$watch('filters', () => this.fetchResults(1)); // Reset to page 1 on filter change
          this.$watch('search', () => this.fetchResults(1)); // Reset to page 1 on search change

          // Handle browser navigation (back/forward)
          window.addEventListener('popstate', () => {
              this.updateFiltersFromQueryString();
              this.fetchResults();
          });
      },
      fetchResults(page = 1) {
          this.loading = true;
          const params = this.getQueryString();
          params.set('paged', page);

          // Update pagination state
          this.pagination.currentPage = page;

          // Update query string in the browser's URL bar
          history.pushState(null, '', '?' + params.toString());

          // Fetch data from the server
          fetch(`${filterAjax.ajax_url}?action=filter_posts&${params.toString()}`)
              .then((res) => res.json())
              .then((data) => {
                  this.results = data.results;
                  this.pagination.totalPages = data.max_num_pages;
                  this.loading = false;
              });
      },
      getQueryString() {
          const params = new URLSearchParams();
          Object.entries(this.filters).forEach(([taxonomy, terms]) => {
              if (terms.length) params.set(taxonomy, terms.join(','));
          });
          if (this.search) params.set('s', this.search);
          return params;
      },
      updateFiltersFromQueryString() {
          const params = new URLSearchParams(location.search);
          this.search = params.get('s') || '';
          ['theme', 'workshop', 'audience'].forEach((taxonomy) => {
              this.filters[taxonomy] = (params.get(taxonomy) || '').split(',').filter(Boolean);
          });
          this.pagination.currentPage = parseInt(params.get('paged')) || 1;
      }
  };
}

