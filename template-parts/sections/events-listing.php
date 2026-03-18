<?php
/**
 * Events listing section shared by the Events page and archive.
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

$args = wp_parse_args(
    $args ?? [],
    [
        'hero_title' => __('Upcoming Events', 'myco'),
        'hero_copy'  => __('Join us for workshops, community service, sports activities, and youth gatherings.', 'myco'),
        'breadcrumb' => [
            ['label' => __('Home', 'myco'), 'url' => home_url('/')],
            ['label' => __('Events', 'myco'), 'url' => ''],
        ],
    ]
);

$categories = get_terms(
    [
        'taxonomy'   => 'event_category',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ]
);

$events_query = new WP_Query(
    [
        'post_type'      => 'event',
        'posts_per_page' => -1,
        'meta_key'       => 'event_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'no_found_rows'  => false,
    ]
);

$events_count  = (int) $events_query->found_posts;
$count_label   = _n('event', 'events', $events_count, 'myco');
$current_month = wp_date('Y-m');
$updates_url   = home_url('/contact/');
$volunteer_url = home_url('/volunteer/');
?>

<section class="events-hero">
    <div class="inner events-hero-inner" style="padding-top: 120px; padding-bottom: 80px; text-align: center;">
        <div class="events-breadcrumb events-breadcrumb--center">
            <?php foreach ($args['breadcrumb'] as $index => $crumb) : ?>
                <?php if ($index > 0) : ?>
                    <svg width="6" height="10" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                        <path d="M1 1l4 4-4 4" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                <?php endif; ?>
                <?php if (!empty($crumb['url'])) : ?>
                    <a href="<?php echo esc_url($crumb['url']); ?>"><?php echo esc_html($crumb['label']); ?></a>
                <?php else : ?>
                    <span><?php echo esc_html($crumb['label']); ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <h1 class="events-hero-title">
            <?php echo esc_html($args['hero_title']); ?>
        </h1>

        <p class="events-hero-copy">
            <?php echo esc_html($args['hero_copy']); ?>
        </p>
    </div>
</section>

<section class="events-page-shell">
    <div class="inner">
        <div class="events-controls">
            <div class="events-filter-tabs">
                <button class="filter-tab active" type="button" data-filter="all"><?php esc_html_e('All Events', 'myco'); ?></button>
                <button class="filter-tab" type="button" data-filter="month"><?php esc_html_e('This Month', 'myco'); ?></button>
                <?php if ($categories && !is_wp_error($categories)) : ?>
                    <?php foreach ($categories as $category) : ?>
                        <button class="filter-tab" type="button" data-filter="<?php echo esc_attr($category->slug); ?>">
                            <?php echo esc_html($category->name); ?>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <label class="events-search" for="events-search-input">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                    <circle cx="8" cy="8" r="5.5" stroke="#64748B" stroke-width="1.6" />
                    <path d="M12 12l4 4" stroke="#64748B" stroke-width="1.6" stroke-linecap="round" />
                </svg>
                <input id="events-search-input" type="search" placeholder="<?php esc_attr_e('Search events, time, or venue', 'myco'); ?>" />
            </label>
        </div>

        <p class="events-results-copy">
            <?php esc_html_e('Showing', 'myco'); ?>
            <span id="count-number"><?php echo esc_html($events_count); ?></span>
            <span id="events-count-label"><?php echo esc_html($count_label); ?></span>
        </p>
    </div>
</section>

<section class="events-page-listing">
    <div class="inner">
        <?php if ($events_query->have_posts()) : ?>
            <div id="events-container" class="events-stack">
                <?php
                while ($events_query->have_posts()) :
                    $events_query->the_post();
                    get_template_part('template-parts/cards/event-card');
                endwhile;
                wp_reset_postdata();
                ?>
            </div>

            <div id="no-results" class="events-no-results">
                <h3><?php esc_html_e('No Events Match That Filter', 'myco'); ?></h3>
                <p><?php esc_html_e('Try another category or check back soon for more opportunities to join us.', 'myco'); ?></p>
            </div>

            <div id="pagination" class="pagination"></div>
        <?php else : ?>
            <div class="events-empty-state">
                <h3><?php esc_html_e('No Events Scheduled Yet', 'myco'); ?></h3>
                <p><?php esc_html_e("We're always planning new gatherings. Check back soon or contact us to stay informed.", 'myco'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="events-cta">
    <div class="inner events-cta-inner">
        <h2 class="events-cta-title"><?php esc_html_e("Don't Miss Out on Our Events", 'myco'); ?></h2>
        <p class="events-cta-copy"><?php esc_html_e('Stay connected with MYCO and be part of our growing community.', 'myco'); ?></p>
        <div class="events-cta-actions">
            <a class="pill-primary" href="<?php echo esc_url($updates_url); ?>"><?php esc_html_e('Subscribe to Updates', 'myco'); ?></a>
            <a class="pill-secondary-light" href="<?php echo esc_url($volunteer_url); ?>"><?php esc_html_e('Volunteer With Us', 'myco'); ?></a>
        </div>
    </div>
</section>

<?php if ($events_count > 0) : ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('events-container');
    const tabs = Array.from(document.querySelectorAll('.events-filter-tabs .filter-tab'));
    const searchInput = document.getElementById('events-search-input');
    const cards = container ? Array.from(container.querySelectorAll('.event-item')) : [];
    const countNumber = document.getElementById('count-number');
    const countLabel = document.getElementById('events-count-label');
    const pagination = document.getElementById('pagination');
    const noResults = document.getElementById('no-results');
    const eventsPerPage = 8;
    const currentMonth = <?php echo wp_json_encode($current_month); ?>;
    let currentFilter = 'all';
    let currentSearch = '';
    let currentPage = 1;

    if (!container || !tabs.length || !cards.length) {
        return;
    }

    function matchesFilter(card) {
        const categories = (card.dataset.categories || '').split(',').filter(Boolean);
        const eventMonth = card.dataset.eventMonth || '';
        const searchValue = (card.dataset.search || '').toLowerCase();

        if (currentFilter === 'all') {
            if (currentSearch && searchValue.indexOf(currentSearch) === -1) {
                return false;
            }

            return true;
        }

        if (currentFilter === 'month') {
            if (eventMonth !== currentMonth) {
                return false;
            }

            if (currentSearch && searchValue.indexOf(currentSearch) === -1) {
                return false;
            }

            return true;
        }

        if (categories.indexOf(currentFilter) === -1) {
            return false;
        }

        if (currentSearch && searchValue.indexOf(currentSearch) === -1) {
            return false;
        }

        return true;
    }

    function updateCount(total) {
        if (countNumber) {
            countNumber.textContent = total;
        }

        if (countLabel) {
            countLabel.textContent = total === 1 ? <?php echo wp_json_encode(__('event', 'myco')); ?> : <?php echo wp_json_encode(__('events', 'myco')); ?>;
        }
    }

    function setVisibleCards(visibleCards) {
        cards.forEach(function (card) {
            card.style.display = 'none';
        });

        visibleCards.forEach(function (card) {
            card.style.display = '';
        });
    }

    function renderPagination(totalPages) {
        if (!pagination) {
            return;
        }

        pagination.innerHTML = '';

        if (totalPages <= 1) {
            return;
        }

        const prevButton = document.createElement('button');
        prevButton.type = 'button';
        prevButton.className = 'pagination-btn';
        prevButton.textContent = '<';
        prevButton.disabled = currentPage === 1;
        prevButton.addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage -= 1;
                updateView();
            }
        });
        pagination.appendChild(prevButton);

        for (let i = 1; i <= totalPages; i += 1) {
            const pageButton = document.createElement('button');
            pageButton.type = 'button';
            pageButton.className = 'pagination-btn' + (i === currentPage ? ' active' : '');
            pageButton.textContent = i;
            pageButton.addEventListener('click', function () {
                currentPage = i;
                updateView();
            });
            pagination.appendChild(pageButton);
        }

        const nextButton = document.createElement('button');
        nextButton.type = 'button';
        nextButton.className = 'pagination-btn';
        nextButton.textContent = '>';
        nextButton.disabled = currentPage === totalPages;
        nextButton.addEventListener('click', function () {
            if (currentPage < totalPages) {
                currentPage += 1;
                updateView();
            }
        });
        pagination.appendChild(nextButton);
    }

    function updateView() {
        const filteredCards = cards.filter(matchesFilter);
        const totalPages = Math.max(1, Math.ceil(filteredCards.length / eventsPerPage));
        const startIndex = (currentPage - 1) * eventsPerPage;
        const pageCards = filteredCards.slice(startIndex, startIndex + eventsPerPage);

        if (currentPage > totalPages) {
            currentPage = 1;
            updateView();
            return;
        }

        updateCount(filteredCards.length);
        setVisibleCards(pageCards);
        renderPagination(filteredCards.length ? totalPages : 0);

        if (noResults) {
            noResults.style.display = filteredCards.length ? 'none' : 'block';
        }
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (item) {
                item.classList.remove('active');
            });

            tab.classList.add('active');
            currentFilter = tab.dataset.filter || 'all';
            currentPage = 1;
            updateView();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            currentSearch = (searchInput.value || '').trim().toLowerCase();
            currentPage = 1;
            updateView();
        });
    }

    updateView();
});
</script>
<?php endif; ?>
