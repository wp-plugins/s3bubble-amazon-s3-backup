<div class="wrap">
	<h2><div class="dashicons dashicons-cloud"></div> Files Manager</h2>
	<div class="s3bubble-backingup-details"></div>
	<?php
		if(!function_exists('curl_multi_exec') || !function_exists('curl_init')) {
			echo "This plugin requires PHP curl to connect to Amazon S3 please contact your hosting to install.";
			exit();
		}
		//Run a S3Bubble security check
		$ajax_nonce = wp_create_nonce( "s3bubble-nonce-security" );

		$surl = S3BUBBLEBACKUP_PLUGIN_PATH . '/classes/vendor/autoload.php';
		if(!class_exists('S3Client'))
	        require_once($surl);
		    use Aws\S3\S3Client;
			require_once('inc/functions.php');

		
	?>
	<div class="postbox-container" style="width: 50%">
		<div class="metabox-holder">
			<div class="postbox">
				<h3 class="hndle"><span>Backup Files</span></h3>
	            <div class="inside s3bubble-running-backup">
	            	<form method="post" id="s3bubble-backup-backingup-form">
	                    <p>
	                        <input type="submit" id="s3bubble-backup-backingup-submit" name="s3bubblecontentcreate" class="button button-primary button-hero" value="Generate Files Backup Now!">
	                    </p>
	                    <p><span class="description">Click to generate a full backup of <code>wp-content/</code> directory. This will backup your themes, plugins and uploaded media. Remember that the generated archive could have hundreds of MBs, even GBs. This function requires PHP <strong>5+</strong> / Linux/Unix server. You have PHP <strong><?php echo PHP_VERSION.' / '.PHP_OS;?></strong>.</span></p>
	                
	                </form>
	                <p>
						For support, feature requests and bug reporting, visit the <a href="https://s3bubble.com/forums/forum/s3bubble-amazon-s3-wordpress-backup-plugin/" target="_blank" rel="external">S3Bubble Community</a>.
					</p>
	            </div>
			</div>
		</div>
	</div>
	<div class="postbox-container" style="width: 50%">
		<div class="metabox-holder">
			<div class="postbox" style="margin: 0 0 0 8px;">
				<h3>S3Bubble Installation Checks</h3>
				<div class="inside">
					<p class="s3bubble-checks-p"><strong>S3Bubble</strong> will now run some checks to make sure your backups will complete successfully.</p>
	                <ul class="s3bubble-checks-list">
	                	<?php echo recursive_directory_size(WP_CONTENT_DIR,TRUE,'s3bubblebackups'); ?>
	                	<li><?php echo implode('</li><li>', system_files()); ?>
						<?php echo gzip_enabled(); ?>
						<?php echo scheduling_active(); ?>
						<?php echo memory_limit(); ?>
	                	<?php echo exec_check(WP_CONTENT_DIR); ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		// Run debug form
		$( "#s3bubble-backup-backingup-form" ).submit(function( event ) {
			$(".s3bubble-running-backup").append("<div class='s3bck'><img src='<?php echo plugins_url( 'assets/images/ajax_loader.gif', __FILE__ ); ?>' /><h2>Running backup please wait do not refresh...</h2></div>");
			$("#s3bubble-backup-backingup-form").slideUp();
			var sendData = {
				action: 's3bubble_backup_files_internal',
				security: '<?php echo $ajax_nonce; ?>'
			}	
			$.post("<?php echo admin_url('admin-ajax.php'); ?>", sendData, function(response) {
				$(".s3bck").html("<h2>" + response + "</h2>");
			},'json');
		  	event.preventDefault();
		});

	});
</script>