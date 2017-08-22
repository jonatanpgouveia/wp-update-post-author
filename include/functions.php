<?php
function update_all_posts_init()
{ ?>
    <div class="container">
      <div class="jumbotron">
        <h2>WP Update Post Author</h2>
        <p>This plugin was developed with the purpose of correcting the posts that may be unrelated to the author.</p>
        <div class="alert alert-info" role="alert">
          <strong>Attention! </strong>The update will apply the logged in user as the author of the posts that are with errors on the front.
        </div>
        <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
          <input type="hidden" name="poststatus" value="poststatus">
          <button type="submit" id="uploadNow" data-loading-text="Loading..." class="btn btn-primary">Corrigir</button>
        </form>
      </div>
    </div>
    <div class="container sucess" style="display: none;">
      <div class="jumbotron">
        <div class="alert alert-success" role="alert">
          <strong>Success! </strong> All posts were changed.
        </div>
        <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
          <input type="hidden" name="poststatus" value="poststatus">
          <button type="submit" id="uploadNow" data-loading-text="Loading..." class="btn btn-primary">New Update</button>
        </form>
      </div>
    </div>
    <div class="container info" style="display: none;">
      <div class="jumbotron">
        <div class="alert alert-success" role="alert">
          <strong>Information! </strong>No posts have been updated!
        </div>
        <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
          <input type="hidden" name="poststatus" value="poststatus">
          <button type="submit" id="uploadNow" data-loading-text="Loading..." class="btn btn-primary">Try again?</button>
        </form>
      </div>
    </div>
<?php }

function my_custom_admin_head() 
{
  if ( isset( $_GET['success'] ) && $_GET['success'] == '1' ) {
      echo '<style>[class="container"] {display: none !important;}</style>';
      echo '<style>[class="container sucess"] {display: block !important;}</style>';
  }if ( isset( $_GET['success'] ) && $_GET['success'] == '0' ){
      echo '<style>[class="container"] {display: none !important;}</style>';
      echo '<style>[class="container info"] {display: block !important;}</style>';
  }
    
}

add_action( 'admin_head', 'my_custom_admin_head' );

function update_all_posts()
{
  global $wpdb;

  $stmt = $wpdb->prepare(
    "UPDATE
      `{$wpdb->posts}`
     SET
      `post_author` = %d
     WHERE
      `post_author` NOT IN(SELECT `ID` FROM `{$wpdb->users}`)
    ",
    intval( get_current_user_id() )
  );

  return $wpdb->query( $stmt );
}