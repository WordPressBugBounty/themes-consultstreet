<?php 
/** 
* Metabox for Page Layout
*
* @package consultstreet
*
*/ 

function consultstreet_add_sidebar_layout_box(){
    add_meta_box(  'consultstreet_sidebar_layout', __( 'Sidebar Layout', 'consultstreet' ), 'consultstreet_sidebar_layout_callback', 'page', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'consultstreet_add_sidebar_layout_box' );

$consultstreet_sidebar_layout = array( 

    'right-sidebar' => array(
         'value'     => 'right-sidebar',
    	 'thumbnail' => get_template_directory_uri() . '/assets/img/icons/theme-right-sidebar.png'         
     ),   
    'no-sidebar'     => array(
    	 'value'     => 'no-sidebar',
    	 'thumbnail' => get_template_directory_uri() . '/assets/img/icons/theme-fullwidth.png'
   	),    
    'left-sidebar' => array(
         'value'     => 'left-sidebar',
    	 'thumbnail' => get_template_directory_uri() . '/assets/img/icons/theme-left-sidebar.png'         
    ),
  
);

function consultstreet_sidebar_layout_callback(){
    global $post , $consultstreet_sidebar_layout;
    wp_nonce_field( basename( __FILE__ ), 'consultstreet_nonce' );
?>
 
<table class="form-table">
    <tr>
        <td colspan="4"><em class="f13"><?php esc_html_e( 'Choose Sidebar Template for the Defult Page Templates', 'consultstreet' ); ?></em></td>
    </tr>

    <tr>
        <td>
        <?php  
            foreach( $consultstreet_sidebar_layout as $field ){  
                $layout = get_post_meta( $post->ID, '_sidebar_layout', true ); ?>

            <div class="radio-image-wrapper" style="float:left; margin-right:30px;">
                <label class="description">
                    <span><img src="<?php echo esc_url( $field['thumbnail'] ); ?>" alt="" /></span><br/>
                    <input type="radio" name="consultstreet_sidebar_layout" value="<?php echo esc_attr( $field['value'] ); ?>" <?php checked( $field['value'], $layout ); if( empty( $layout ) ){ checked( $field['value'], 'right-sidebar' ); }?>/>
                </label>
            </div>
            <?php } // end foreach 
            ?>
            <div class="clear"></div>
        </td>
    </tr>
</table>
 
<?php 
}

function consultstreet_save_sidebar_layout( $post_id ){
      global $consultstreet_sidebar_layout , $post;

       // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'consultstreet_nonce' ] ) || !wp_verify_nonce( $_POST[ 'consultstreet_nonce' ], basename( __FILE__ ) ) )
        return;
    
 // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
        return;

    if ('page' == isset($_POST['post_type'])) {  
        if (!current_user_can( 'edit_page', $post_id ) )  
            return $post_id;	
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
            return $post_id;  
    }
    foreach( $consultstreet_sidebar_layout as $field ){  
        //Execute this saving function
        $old = get_post_meta( $post_id, '_sidebar_layout', true );
		$new = isset( $_POST['consultstreet_sidebar_layout'] ) ? sanitize_key( $_POST['consultstreet_sidebar_layout'] ) : 'right-sidebar';
        if( $new && $new != $old ) {  
            update_post_meta( $post_id, '_sidebar_layout', $new );  
        }elseif( '' == $new && $old ) {  
            delete_post_meta( $post_id, '_sidebar_layout', $old );  
        } 
     } // end foreach     
}
add_action( 'save_post' , 'consultstreet_save_sidebar_layout' );