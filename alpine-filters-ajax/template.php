<div x-data="postFilter()" class="post-filter">
    
    <!-- Search -->
    <input type="text" x-model="search" placeholder="Begin typing to search posts...">

    <!-- Filters -->
    <div class="filters">
        <?php foreach (['theme', 'workshop', 'audience'] as $taxonomy): ?>
            <div class="filter-group">
                <h3>Filter by <?php echo ucfirst($taxonomy); ?></h3>
                <?php $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]); ?>
                <div class="filter-chips">
                <?php foreach ($terms as $term): ?>
                    <label>
                        <input
                            type="checkbox"
                            x-model="filters['<?php echo $taxonomy; ?>']"
                            value="<?php echo esc_attr($term->slug); ?>">
                        <?php echo esc_html($term->name); ?>
                    </label>
                <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


    <!-- Results -->
    <div class="posts-list">

        <template x-for="post in results" :key="post.id">

            <article class="post-card" :id="'post-' + post.id">

                <div class="post-card__title">
                    <h2 x-text="post.title"></h2>
                    <span x-text="post.author ? ' - ' + post.author : ''"></span>
                </div>

                <a :href="post.link">
                </a>

            </article><!-- #post-## -->

        </template>
    </div>

    <!-- Loading -->
    <div x-show="loading === true">
        <p>Loading posts...</p>
    </div>
    <!-- No Results -->
    <div x-show="results.length === 0 && loading === false">
        <p>No posts found with current filters...</p>
    </div>

    <!-- Pagination -->
    <div class="pagination" x-show="pagination.totalPages > 1">
        <button
            x-show="pagination.currentPage > 1"
            @click="fetchResults(pagination.currentPage - 1)">
            Previous
        </button>
        <span x-text="`Page ${pagination.currentPage} of ${pagination.totalPages}`"></span>
        <button
            x-show="pagination.currentPage < pagination.totalPages"
            @click="fetchResults(pagination.currentPage + 1)">
            Next
        </button>
    </div>
</div>