<?php
/**
 * Template for displaying project archives
 */

get_header();
?>

<main id="primary" class="site-main">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('Projects', 'wp-custom-theme'); ?></h1>
        <p class="archive-description"><?php esc_html_e('Explore our portfolio of completed projects', 'wp-custom-theme'); ?></p>
    </header>
    
 
    <div class="projects-container">
        <div id="projects-grid" class="projects-grid">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    get_template_part('templates/project', 'item');
                endwhile;
            else :
                ?>
                <div class="no-projects-found">
                    <h2><?php esc_html_e('No projects found', 'wp-custom-theme'); ?></h2>
                    <p><?php esc_html_e('Sorry, no projects match your criteria.', 'wp-custom-theme'); ?></p>
                </div>
                <?php
            endif;
            ?>
        </div>
        
        <?php if (have_posts()) : ?>
            <div class="pagination-wrapper">
                <?php
                the_posts_pagination(array(
                    'prev_text' => esc_html__('← Previous', 'wp-custom-theme'),
                    'next_text' => esc_html__('Next →', 'wp-custom-theme'),
                ));
                ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div id="loading" class="loading-spinner" style="display: none;">
        <p><?php esc_html_e('Loading projects...', 'wp-custom-theme'); ?></p>
    </div>
</main>

<style>
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.project-item {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.project-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.project-thumbnail {
    position: relative;
    overflow: hidden;
}

.project-thumbnail img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.project-item:hover .project-thumbnail img {
    transform: scale(1.05);
}

.project-content {
    padding: 1.5rem;
}

.project-title {
    margin-bottom: 0.5rem;
    font-size: 1.3rem;
}

.project-title a {
    color: #333;
    text-decoration: none;
}

.project-title a:hover {
    color: #0073aa;
}

.project-excerpt {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.project-meta-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
    font-size: 0.9rem;
    color: #666;
}

.project-date-range {
    font-style: italic;
}

.project-view-link {
    color: #0073aa;
    text-decoration: none;
    font-weight: bold;
}

.project-view-link:hover {
    text-decoration: underline;
}

.project-categories {
    margin-top: 1rem;
}

.project-category-tag {
    display: inline-block;
    background: #f0f0f0;
    color: #333;
    padding: 0.25rem 0.5rem;
    border-radius: 3px;
    font-size: 0.8rem;
    text-decoration: none;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
}

.project-category-tag:hover {
    background: #333;
    color: #fff;
}

.no-projects-found {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem;
    background: #f9f9f9;
    border-radius: 8px;
}

.loading-spinner {
    text-align: center;
    padding: 2rem;
    color: #666;
}

.projects-container {
    position: relative;
}

/* Responsive */
@media (max-width: 768px) {
    .projects-grid {
        grid-template-columns: 1fr;
    }
    
    .project-meta-summary {
        flex-direction: column;
        gap: 0.5rem;
        align-items: stretch;
    }
}

/* Loading state */
.projects-container.loading .projects-grid {
    opacity: 0.5;
    pointer-events: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('project-filter-form');
    const projectsGrid = document.getElementById('projects-grid');
    const loading = document.getElementById('loading');
    const clearFilters = document.getElementById('clear-filters');
    const projectsContainer = document.querySelector('.projects-container');
    
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            filterProjects();
        });
    }
    
    if (clearFilters) {
        clearFilters.addEventListener('click', function() {
            filterForm.reset();
            filterProjects();
        });
    }
    
    function filterProjects() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        
        for (const [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }
        
        params.append('action', 'filter_projects');
        params.append('nonce', wp_custom_ajax.nonce);
        
        // Show loading state
        projectsContainer.classList.add('loading');
        loading.style.display = 'block';
        
        fetch(wp_custom_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: params.toString()
        })
        .then(response => response.text())
        .then(data => {
            projectsGrid.innerHTML = data;
            projectsContainer.classList.remove('loading');
            loading.style.display = 'none';
        })
        .catch(error => {
            console.error('Error:', error);
            projectsContainer.classList.remove('loading');
            loading.style.display = 'none';
            projectsGrid.innerHTML = '<div class="no-projects-found"><h2>Error loading projects</h2><p>Please try again later.</p></div>';
        });
    }
});
</script>

<?php
get_footer();