<?php if ( ! defined( 'FW' ) )  { die( 'Forbidden' ); }

class FW_Extension_Lastimosa_Team_Member extends FW_Extension {

    private $post_type = 'team-member';
    private $slug = 'team-member';

    /**
     * @internal
     */
    public function _init() {
        $this->define_slugs();

        add_action( 'init', array( $this, '_action_register_post_type' ) );

        if ( is_admin() ) {
            $this->add_admin_actions();
            $this->add_admin_filters();
        }
    }

    private function define_slugs() {
        $this->slug = apply_filters( 'fw_ext_lastimosa_team_member_post_slug', $this->slug );
    }

    public function add_admin_actions() {
        add_action( 'admin_menu', array( $this, '_action_admin_rename_posts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, '_action_admin_add_static' ) );
    }

    public function add_admin_filters() {
    add_filter( 'fw_post_options', array( $this, '_filter_admin_add_post_options' ), 10, 2 );
    }

    /**
     * @internal
     */
    public function _action_admin_add_static() {
        $listing_screen  = array(
            'only' => array(
                array(
                    'post_type' => $this->post_type,
                    'base'      => array( 'edit' )
                )
            )
        );
        $add_edit_screen = array(
            'only' => array(
                array(
                    'post_type' => $this->post_type,
                    'base'      => 'post'
                )
            )
        );

        if ( fw_current_screen_match( $listing_screen ) ) {
            wp_enqueue_style(
                'fw-extension-' . $this->get_name() . '-listing',
                plugin_dir_URL(__FILE__) . '/static/css/admin-listing.css',
                array(),
                fw()->manifest->get_version()
            );
        }

        if ( fw_current_screen_match( $add_edit_screen ) ) {
            wp_enqueue_style(
                'fw-extension-' . $this->get_name() . '-add-edit',
                plugin_dir_URL(__FILE__).'/static/css/admin-add-edit.css',
                array(),
                fw()->manifest->get_version()
            );
            wp_enqueue_script(
                'fw-extension-' . $this->get_name() . '-add-edit',
                plugin_dir_URL(__FILE__) . '/static/js/admin-add-edit.js',
                array( 'jquery' ),
                fw()->manifest->get_version(),
                true
            );
        }
    }

    /**
     * @internal
     */
    public function _action_register_post_type() {

        $post_names = apply_filters( 'fw_ext_sliders_post_type_name', array(
            'singular' => __( 'Team Member', 'lastimosa' ),
            'plural'   => __( 'Team Members', 'lastimosa' )
        ) );

        register_post_type( $this->post_type, array(
            'labels'             => array(
                'name'               => $post_names['plural'], //__( 'Portfolio', 'lastimosa' ),
                'singular_name'      => $post_names['singular'], //__( 'Portfolio project', 'lastimosa' ),
                'add_new'            => __( 'Add New', 'lastimosa' ),
                'add_new_item'       => sprintf( __( 'Add New %s', 'lastimosa' ), $post_names['singular'] ),
                'edit'               => __( 'Edit', 'lastimosa' ),
                'edit_item'          => sprintf( __( 'Edit %s', 'lastimosa' ), $post_names['singular'] ),
                'new_item'           => sprintf( __( 'New %s', 'lastimosa' ), $post_names['singular'] ),
                'all_items'          => sprintf( __( 'All %s', 'lastimosa' ), $post_names['plural'] ),
                'view'               => sprintf( __( 'View %s', 'lastimosa' ), $post_names['singular'] ),
                'view_item'          => sprintf( __( 'View %s', 'lastimosa' ), $post_names['singular'] ),
                'search_items'       => sprintf( __( 'Search %s', 'lastimosa' ), $post_names['plural'] ),
                'not_found'          => sprintf( __( 'No %s Found', 'lastimosa' ), $post_names['plural'] ),
                'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'lastimosa' ), $post_names['plural'] ),
                'parent_item_colon'  => '' /* text for parent types */
            ),
            'description'        => __( 'Create a POST TYPE SINGULAR', 'lastimosa' ),
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'publicly_queryable' => false,
            /* queries can be performed on the front end */
            'has_archive'        => true,
            'rewrite'            => array(
                'slug' => $this->slug
            ),
            'menu_position'      => 4,
            'show_in_nav_menus'  => false,
            'menu_icon'          => 'dashicons-media-code',
            'hierarchical'       => false,
            'query_var'          => true,
            /* Sets the query_var key for this post type. Default: true - set to $post_type */
            'supports'           => array(
                'title', /* Text input field to create a post title. */
                // 'editor',
                // 'thumbnail', /* Displays a box for featured image. */
            ),
            'capabilities'       => array(
                'edit_post'              => 'edit_pages',
                'read_post'              => 'edit_pages',
                'delete_post'            => 'edit_pages',
                'edit_posts'             => 'edit_pages',
                'edit_others_posts'      => 'edit_pages',
                'publish_posts'          => 'edit_pages',
                'read_private_posts'     => 'edit_pages',
                'read'                   => 'edit_pages',
                'delete_posts'           => 'edit_pages',
                'delete_private_posts'   => 'edit_pages',
                'delete_published_posts' => 'edit_pages',
                'delete_others_posts'    => 'edit_pages',
                'edit_private_posts'     => 'edit_pages',
                'edit_published_posts'   => 'edit_pages',
            ),
        ) );
    }

    /**
     * @internal
     *
     * @param array $options
     * @param string $post_type
     *
     * @return array
     */
    public function _filter_admin_add_post_options( $options, $post_type ) {
        if ( $post_type === $this->post_type ) {
          $path = dirname(__FILE__).'/options.php';
          $variables = fw_get_variables_from_file($path, array('options' => array()), $options );
          $options[] = array( $variables['options'] );
        }
        return $options;
    }

    /**
     * internal
     */
    public function _action_admin_rename_posts() {
        global $menu;

        foreach ( $menu as $key => $menu_item ) {
            if ( $menu_item[2] == 'edit.php?post_type=' . $this->post_type ) {
                $menu[ $key ][0] = __( 'Team Member', 'lastimosa' );
            }
        }
    }

    /**
     * @internal
     *
     * @return string
     */
    public function _get_link() {
        return self_admin_url('edit.php?post_type=' . $this->post_type);
    }

    public function get_settings() {

        $response = array(
            'post_type'     => $this->post_type,
            'slug'          => $this->slug,
        );

        return $response;
    }

    public function get_post_type_name() {
        return $this->post_type;
    }
}