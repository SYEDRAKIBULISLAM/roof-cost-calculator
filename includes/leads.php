<?php

add_action('admin_menu', 'leads_menu');

function leads_menu() {
    add_menu_page(
        'Leads',
        'Leads',
        'manage_options',
        'leads',
        'display_leads_page',
        'dashicons-list-view',
        6
    );

    add_submenu_page(
        null, // Setting parent slug as null so it's not displayed in the menu
        'View Lead',
        'View Lead',
        'manage_options',
        'view-lead',
        'view_lead_page'
    );
}

function display_leads_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'calculator_data';


    // Handle bulk delete operation
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'bulk-delete' && !empty($_POST['lead'])) {
            $lead_ids = array_map('intval', $_POST['lead']);
            $ids_placeholder = implode(',', array_fill(0, count($lead_ids), '%d'));

            $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id IN ($ids_placeholder)", $lead_ids));
            echo "<div class='notice notice-success is-dismissible'><p>Selected leads deleted successfully.</p></div>";
        }
    }


    // Fetch data for display with pagination
    $limit = 10; // Records per page
    $total_records = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    $total_pages = ceil($total_records / $limit);
    $current_page = isset($_GET['page_number']) ? max(1, (int)$_GET['page_number']) : 1;
    $offset = ($current_page - 1) * $limit;
    $leads = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY id DESC LIMIT %d OFFSET %d", $limit, $offset));




    // Form for bulk actions
    echo '<form method="post">';
    echo '<input type="hidden" name="action" value="bulk-delete">';
    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Leads</h1>';

    // Export Button
    echo '<div class="alignleft actions" style="float:right">';
    echo '<a href="'. esc_url(add_query_arg('action', 'export_leads', admin_url('admin-post.php'))) .'" class="button action">Export CSV</a>';
    echo '</div>';
    echo '<hr/>';

    echo '<hr class="wp-header-end">';
    
    // Top Pagination
    $start = ($current_page - 1) * $limit + 1;
    $end = min($current_page * $limit, $total_records);
    
    echo '<div class="tablenav top">';
    echo '<div class="tablenav-pages">';
    echo '<span class="displaying-num">' . sprintf('%d&ndash;%d of %d', $start, $end, $total_records) . '</span>';
    
    if ($total_pages > 1) {
        
        $base_url = admin_url('admin.php?page=leads');
        
        // Build pagination links
        $pagination_html = '<span class="pagination-links">';
        
        // Previous button
        if ($current_page > 1) {
            $prev_url = add_query_arg('page_number', $current_page - 1, $base_url);
            $pagination_html .= '<a class="first-page button" href="' . esc_url($base_url) . '"><span class="screen-reader-text">First page</span><span aria-hidden="true">&laquo;</span></a>';
            $pagination_html .= '<a class="prev-page button" href="' . esc_url($prev_url) . '"><span class="screen-reader-text">Previous page</span><span aria-hidden="true">&lsaquo;</span></a>';
        } else {
            $pagination_html .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&laquo;</span>';
            $pagination_html .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&lsaquo;</span>';
        }
        
        // Page info
        $pagination_html .= '<span class="paging-input">';
        $pagination_html .= '<label for="current-page-selector-top" class="screen-reader-text">Current Page</label>';
        $pagination_html .= '<input class="current-page" id="current-page-selector-top" type="text" name="paged" value="' . $current_page . '" size="2" aria-describedby="table-paging">';
        $pagination_html .= '<span class="tablenav-paging-text"> of <span class="total-pages">' . $total_pages . '</span></span>';
        $pagination_html .= '</span>';
        
        // Next button
        if ($current_page < $total_pages) {
            $next_url = add_query_arg('page_number', $current_page + 1, $base_url);
            $pagination_html .= '<a class="next-page button" href="' . esc_url($next_url) . '"><span class="screen-reader-text">Next page</span><span aria-hidden="true">&rsaquo;</span></a>';
            $last_url = add_query_arg('page_number', $total_pages, $base_url);
            $pagination_html .= '<a class="last-page button" href="' . esc_url($last_url) . '"><span class="screen-reader-text">Last page</span><span aria-hidden="true">&raquo;</span></a>';
        } else {
            $pagination_html .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
            $pagination_html .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&raquo;</span>';
        }
        
        $pagination_html .= '</span>';
        
        echo $pagination_html;
    }
    
    echo '</div>';
    echo '</div>';
    
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr>';
    echo '<th scope="col" class="manage-column column-cb check-column"><input type="checkbox" id="cb-select-all"></th>';
    echo '<th scope="col" class="manage-column column-id">ID</th>';
    echo '<th scope="col" class="manage-column column-name">First Name</th>';
    echo '<th scope="col" class="manage-column column-name">Last Name</th>';
    echo '<th scope="col" class="manage-column column-email">Email</th>';
    echo '<th scope="col" class="manage-column column-actions">Actions</th>';
    echo '</tr></thead><tbody>';

    if ($leads) {
        foreach ($leads as $lead) {
            echo '<tr>';
            echo '<th scope="row" class="check-column"><input type="checkbox" name="lead[]" value="' . esc_attr($lead->id) . '" /></th>';
            echo '<td>' . esc_html($lead->id) . '</td>';
            echo '<td>' . esc_html($lead->first_name) . '</td>';
            echo '<td>' . esc_html($lead->last_name) . '</td>';
            echo '<td>' . esc_html($lead->email) . '</td>';
            echo '<td>';
            echo '<a href="' . admin_url('admin.php?page=view-lead&lead_id=' . $lead->id) . '">View</a> | ';
            echo '<a href="' . admin_url('admin.php?page=leads&delete_id=' . $lead->id) . '" onclick="return confirm(\'Are you sure you want to delete this lead?\')">Delete</a>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="6">No leads found.</td></tr>';
    }

    echo '</tbody>';
    echo '<tfoot><tr>';
    echo '<th scope="col" class="manage-column column-cb check-column"><input type="checkbox" id="cb-select-all-2"></th>';
    echo '<th scope="col" class="manage-column column-id">ID</th>';
    echo '<th scope="col" class="manage-column column-name">First Name</th>';
    echo '<th scope="col" class="manage-column column-name">Last Name</th>';
    echo '<th scope="col" class="manage-column column-email">Email</th>';
    echo '<th scope="col" class="manage-column column-actions">Actions</th>';
    echo '</tr></tfoot>';
    echo '</table>';

    // Bottom Pagination
    $start = ($current_page - 1) * $limit + 1;
    $end = min($current_page * $limit, $total_records);
    
    echo '<div class="tablenav">';
    echo '<div class="tablenav-pages">';
    echo '<span class="displaying-num">' . sprintf('%d&ndash;%d of %d', $start, $end, $total_records) . '</span>';
    
    if ($total_pages > 1) {
        
        $base_url = admin_url('admin.php?page=leads');
        
        // Build pagination links
        $pagination_html = '<span class="pagination-links">';
        
        // Previous button
        if ($current_page > 1) {
            $prev_url = add_query_arg('page_number', $current_page - 1, $base_url);
            $pagination_html .= '<a class="first-page button" href="' . esc_url($base_url) . '"><span class="screen-reader-text">First page</span><span aria-hidden="true">&laquo;</span></a>';
            $pagination_html .= '<a class="prev-page button" href="' . esc_url($prev_url) . '"><span class="screen-reader-text">Previous page</span><span aria-hidden="true">&lsaquo;</span></a>';
        } else {
            $pagination_html .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&laquo;</span>';
            $pagination_html .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&lsaquo;</span>';
        }
        
        // Page info
        $pagination_html .= '<span class="paging-input">';
        $pagination_html .= '<label for="current-page-selector" class="screen-reader-text">Current Page</label>';
        $pagination_html .= '<input class="current-page" id="current-page-selector" type="text" name="paged" value="' . $current_page . '" size="2" aria-describedby="table-paging">';
        $pagination_html .= '<span class="tablenav-paging-text"> of <span class="total-pages">' . $total_pages . '</span></span>';
        $pagination_html .= '</span>';
        
        // Next button
        if ($current_page < $total_pages) {
            $next_url = add_query_arg('page_number', $current_page + 1, $base_url);
            $pagination_html .= '<a class="next-page button" href="' . esc_url($next_url) . '"><span class="screen-reader-text">Next page</span><span aria-hidden="true">&rsaquo;</span></a>';
            $last_url = add_query_arg('page_number', $total_pages, $base_url);
            $pagination_html .= '<a class="last-page button" href="' . esc_url($last_url) . '"><span class="screen-reader-text">Last page</span><span aria-hidden="true">&raquo;</span></a>';
        } else {
            $pagination_html .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
            $pagination_html .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&raquo;</span>';
        }
        
        $pagination_html .= '</span>';
        
        echo $pagination_html;
    }
    
    echo '</div>';
    echo '</div>';

    // Bulk delete button and export button
    echo '<div class="tablenav bottom">';
    echo '<div class="alignleft actions">';
    echo '<input type="submit" class="button action" value="Delete Selected">';
    echo '</div>';

    echo '<br class="clear" />';
    echo '</div>'; // End of tablenav

    echo '</div>';
    echo '</form>';

    // Add JavaScript for pagination input
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#current-page-selector, #current-page-selector-top').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                var page = parseInt($(this).val());
                var totalPages = parseInt($('.total-pages').first().text());
                if (page >= 1 && page <= totalPages) {
                    var baseUrl = '<?php echo admin_url('admin.php?page=leads'); ?>';
                    window.location.href = baseUrl + '&page_number=' + page;
                } else {
                    alert('Please enter a valid page number between 1 and ' + totalPages);
                }
            }
        });
    });
    </script>
    <?php

}

function view_lead_page() {
    // Check if the lead_id is provided
    if (!isset($_GET['lead_id'])) {
        echo '<div class="notice notice-error"><p>No lead ID provided.</p></div>';
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'calculator_data';
    $lead_id = intval($_GET['lead_id']);

    // Retrieve the lead data
    $lead = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $lead_id));

    // Check if the lead exists
    if (!$lead) {
        echo '<div class="notice notice-error"><p>Lead not found.</p></div>';
        return;
    }

    // Display lead details using WordPress admin styling
    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Lead Details</h1><hr/>';
    echo '<hr class="wp-header-end">';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<tbody>';
    echo '<tr><th scope="row">ID</th><td>' . esc_html($lead->id) . '</td></tr>';
    echo '<tr><th scope="row">First Name</th><td>' . esc_html($lead->first_name) . '</td></tr>';
    echo '<tr><th scope="row">Last Name</th><td>' . esc_html($lead->last_name) . '</td></tr>';
    echo '<tr><th scope="row">Zip</th><td>' . esc_html($lead->zip) . '</td></tr>';
    echo '<tr><th scope="row">Phone</th><td>' . esc_html($lead->phone) . '</td></tr>';
    echo '<tr><th scope="row">Email</th><td>' . esc_html($lead->email) . '</td></tr>';
    echo '<tr><th scope="row">Notes</th><td>' . nl2br(htmlspecialchars($lead->notes)) . '</td></tr>';
    echo '</tbody>';
    echo '</table><hr/>';
    echo '<a href="' . admin_url('admin.php?page=leads') . '" class="button">Back to Leads</a>';
    echo '</div>';
}

function export_leads_handler() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'export_leads') {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'calculator_data';

    $leads = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    if (empty($leads)) {
        wp_die('No leads available to export.');
    }

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="leads.csv"');

    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF");
    fputcsv($output, array('ID', 'First Name', 'Last Name', 'Email', 'Zip', 'Phone', 'Notes', 'Created At'));

    foreach ($leads as $lead) {
        fputcsv($output, array(
            $lead['id'],
            $lead['first_name'],
            $lead['last_name'],
            $lead['email'],
            $lead['zip'],
            $lead['phone'],
            $lead['notes'],
            $lead['created_at']
        ));
    }

    fclose($output);
    exit;
}
add_action('admin_init', 'export_leads_handler');