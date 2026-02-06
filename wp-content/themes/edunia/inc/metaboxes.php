<?php
function agenda_meta_box_markup($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div class="custom-meta-box">
			<p>
			<label for="date"><?php echo __('Date:', 'edunia');?></label>
            <input name="agenda-date" type="date" id="date" value="<?php echo get_post_meta($object->ID, "agenda-date", true); ?>">
			</p>

			<p>
			<label for="start-time"><?php echo __('Start Time:', 'edunia');?></label>
            <input name="agenda-starttime" type="time" id="start-time" value="<?php echo get_post_meta($object->ID, "agenda-starttime", true); ?>">
			</p>

			<p>
			<label for="end-time"><?php echo __('Time\'s Up:', 'edunia');?></label>
            <input name="agenda-endtime" type="time" id="end-time" value="<?php echo get_post_meta($object->ID, "agenda-endtime", true); ?>">
			</p>

			<hr>

			<p>
			<label for="location"><?php echo __('Location:', 'edunia');?></label>
            <input name="agenda-location" type="text" id="location" value="<?php echo get_post_meta($object->ID, "agenda-location", true); ?>">
			</p>
        </div>
    <?php  
}

function add_agenda_meta_box() {
    add_meta_box("agenda-meta-box", __('Time & Location', 'edunia'), "agenda_meta_box_markup", "agenda", "side", "high", null);
}
add_action("add_meta_boxes", "add_agenda_meta_box");

function save_agenda_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "agenda";
    if($slug != $post->post_type)
        return $post_id;

		$agenda_date_value = "";
		$agenda_starttime_value = "";
		$agenda_endtime_value = "";
		$agenda_location_value = "";

	if(isset($_POST["agenda-date"])) {
		$agenda_date_value = $_POST["agenda-date"];
	}   
	update_post_meta($post_id, "agenda-date", $agenda_date_value);

	if(isset($_POST["agenda-starttime"])) {
		$agenda_starttime_value = $_POST["agenda-starttime"];
	}   
	update_post_meta($post_id, "agenda-starttime", $agenda_starttime_value);

	if(isset($_POST["agenda-endtime"])) {
		$agenda_endtime_value = $_POST["agenda-endtime"];
	}   
	update_post_meta($post_id, "agenda-endtime", $agenda_endtime_value);

    if(isset($_POST["agenda-location"])) {
        $agenda_location_value = $_POST["agenda-location"];
    }   
    update_post_meta($post_id, "agenda-location", $agenda_location_value);
}
add_action("save_post", "save_agenda_meta_box", 10, 3);

function video_meta_box_markup($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div class="custom-meta-box">
            <p>
            <input name="youtube-url" type="text" id="youtube-url" value="<?php echo get_post_meta($object->ID, "youtube-url", true); ?>" placeholder="https://youtu.be/xxx">
            </p>
        </div>
    <?php  
}

function add_video_meta_box() {
    add_meta_box("video-meta-box", __('Youtube Url', 'edunia'), "video_meta_box_markup", "video", "side", "high", null);
}
add_action("add_meta_boxes", "add_video_meta_box");

function save_video_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "video";
    if($slug != $post->post_type)
        return $post_id;

        $video_youtubeurl_value = "";

    if(isset($_POST["youtube-url"])) {
        $video_youtubeurl_value = $_POST["youtube-url"];
    }   
    update_post_meta($post_id, "youtube-url", $video_youtubeurl_value);
}
add_action("save_post", "save_video_meta_box", 10, 3);

function gtk_meta_box_markup($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div class="custom-meta-box">
			<p>
			<label for="position"><?php echo __('Position:', 'edunia');?></label>
            <input name="gtk-position" type="text" id="position" value="<?php echo get_post_meta($object->ID, "gtk-position", true); ?>">
			</p>

			<p>
			<label for="status"><?php echo __('Status:', 'edunia');?></label>
            <input name="gtk-status" type="text" id="status" value="<?php echo get_post_meta($object->ID, "gtk-status", true); ?>">
			</p>

			<p>
			<label for="nip"><?php echo __('NIP:', 'edunia');?></label>
            <input name="gtk-nip" type="text" id="nip" value="<?php echo get_post_meta($object->ID, "gtk-nip", true); ?>">
			</p>

			<p>
			<label for="nuptk"><?php echo __('NUPTK:', 'edunia');?></label>
            <input name="gtk-nuptk" type="text" id="nuptk" value="<?php echo get_post_meta($object->ID, "gtk-nuptk", true); ?>">
			</p>

			<p>
			<label for="active"><?php echo __('Active:', 'edunia');?></label>
            <input name="gtk-active" type="text" id="active" value="<?php echo get_post_meta($object->ID, "gtk-active", true); ?>">
			</p>

			<p>
			<label for="gender"><?php echo __('Gender:', 'edunia');?></label>
            <input name="gtk-gender" type="text" id="gender" value="<?php echo get_post_meta($object->ID, "gtk-gender", true); ?>">
			</p>

			<p>
			<label for="ttl"><?php echo __('Place & Date of Birth:', 'edunia');?></label>
            <input name="gtk-ttl" type="text" id="ttl" value="<?php echo get_post_meta($object->ID, "gtk-ttl", true); ?>">
			</p>

			<p>
			<label for="religion"><?php echo __('Religion:', 'edunia');?></label>
            <input name="gtk-religion" type="text" id="religion" value="<?php echo get_post_meta($object->ID, "gtk-religion", true); ?>">
			</p>

			<p>
			<label for="phone"><?php echo __('Phone:', 'edunia');?></label>
            <input name="gtk-phone" type="text" id="phone" value="<?php echo get_post_meta($object->ID, "gtk-phone", true); ?>">
			</p>

			<p>
			<label for="email"><?php echo __('Email:', 'edunia');?></label>
            <input name="gtk-email" type="text" id="email" value="<?php echo get_post_meta($object->ID, "gtk-email", true); ?>">
			</p>

			<p>
			<label for="whatsapp"><?php echo __('WhatsApp:', 'edunia');?></label>
            <input name="gtk-whatsapp" type="text" id="whatsapp" value="<?php echo get_post_meta($object->ID, "gtk-whatsapp", true); ?>">
			</p>

			<p>
			<label for="address"><?php echo __('Address:', 'edunia');?></label>
            <textarea name="gtk-address" id="religion"><?php echo get_post_meta($object->ID, "gtk-address", true); ?></textarea>
			</p>
        </div>
    <?php  
}

function add_gtk_meta_box() {
    add_meta_box("gtk-meta-box", __('Details', 'edunia'), "gtk_meta_box_markup", "gtk", "side", "low", null);
}
add_action("add_meta_boxes", "add_gtk_meta_box");

function save_gtk_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "gtk";
    if($slug != $post->post_type)
        return $post_id;

		$gtk_position_value = "";
		$gtk_status_value = "";
		$gtk_nip_value = "";
		$gtk_nuptk_value = "";
		$gtk_active_value = "";
		$gtk_gender_value = "";
		$gtk_ttl_value = "";
		$gtk_religion_value = "";
		$gtk_phone_value = "";
		$gtk_email_value = "";
		$gtk_whatsapp_value = "";
		$gtk_address_value = "";

    if(isset($_POST["gtk-position"])) {
        $gtk_position_value = $_POST["gtk-position"];
    }   
	update_post_meta($post_id, "gtk-position", $gtk_position_value);

	if(isset($_POST["gtk-status"])) {
        $gtk_status_value = $_POST["gtk-status"];
    }   
	update_post_meta($post_id, "gtk-status", $gtk_status_value);

	if(isset($_POST["gtk-nip"])) {
        $gtk_nip_value = $_POST["gtk-nip"];
    }   
	update_post_meta($post_id, "gtk-nip", $gtk_nip_value);

	if(isset($_POST["gtk-nuptk"])) {
        $gtk_nuptk_value = $_POST["gtk-nuptk"];
    }   
	update_post_meta($post_id, "gtk-nuptk", $gtk_nuptk_value);

	if(isset($_POST["gtk-active"])) {
        $gtk_active_value = $_POST["gtk-active"];
    }   
	update_post_meta($post_id, "gtk-active", $gtk_active_value);

	if(isset($_POST["gtk-gender"])) {
        $gtk_gender_value = $_POST["gtk-gender"];
    }   
	update_post_meta($post_id, "gtk-gender", $gtk_gender_value);

	if(isset($_POST["gtk-ttl"])) {
        $gtk_ttl_value = $_POST["gtk-ttl"];
    }   
	update_post_meta($post_id, "gtk-ttl", $gtk_ttl_value);

	if(isset($_POST["gtk-religion"])) {
        $gtk_religion_value = $_POST["gtk-religion"];
    }   
	update_post_meta($post_id, "gtk-religion", $gtk_religion_value);

	if(isset($_POST["gtk-phone"])) {
        $gtk_phone_value = $_POST["gtk-phone"];
    }   
	update_post_meta($post_id, "gtk-phone", $gtk_phone_value);

	if(isset($_POST["gtk-email"])) {
        $gtk_email_value = $_POST["gtk-email"];
    }   
	update_post_meta($post_id, "gtk-email", $gtk_email_value);

	if(isset($_POST["gtk-whatsapp"])) {
        $gtk_whatsapp_value = $_POST["gtk-whatsapp"];
    }   
	update_post_meta($post_id, "gtk-whatsapp", $gtk_whatsapp_value);

	if(isset($_POST["gtk-address"])) {
        $gtk_address_value = $_POST["gtk-address"];
    }   
	update_post_meta($post_id, "gtk-address", $gtk_address_value);
}
add_action("save_post", "save_gtk_meta_box", 10, 3);

function testimonial_side_meta_box_markup($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div class="custom-meta-box">
			<p>
            <input name="testimonial-position" type="text" id="position" value="<?php echo get_post_meta($object->ID, "testimonial-position", true); ?>">
			</p>
        </div>
    <?php  
}

function add_testimonial_side_meta_box() {
    add_meta_box("testimonial-meta-box-details", __('Position', 'edunia'), "testimonial_side_meta_box_markup", "testimonial", "side", "high", null);
}
add_action("add_meta_boxes", "add_testimonial_side_meta_box");

function save_testimonial_side_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "testimonial";
    if($slug != $post->post_type)
        return $post_id;
		$testimonial_position_value = "";

    if(isset($_POST["testimonial-position"])) {
        $testimonial_position_value = $_POST["testimonial-position"];
    }   
    update_post_meta($post_id, "testimonial-position", $testimonial_position_value);
}
add_action("save_post", "save_testimonial_side_meta_box", 10, 3);

function testimonial_normal_meta_box_markup($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div class="custom-meta-box metabox-testimonial-message">
			<p>
            <textarea name="testimonial-message" type="text" id="message" rows="5"><?php echo get_post_meta($object->ID, "testimonial-message", true); ?></textarea>
			</p>
        </div>
    <?php  
}

function add_testimonial_normal_meta_box() {
    add_meta_box("testimonial-meta-box-message", __('Testimonial', 'edunia'), "testimonial_normal_meta_box_markup", "testimonial", "normal", "high", null);
}
add_action("add_meta_boxes", "add_testimonial_normal_meta_box");

function save_testimonial_normal_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "testimonial";
    if($slug != $post->post_type)
        return $post_id;
		$testimonial_message_value = "";

    if(isset($_POST["testimonial-message"])) {
        $testimonial_message_value = $_POST["testimonial-message"];
    }   
    update_post_meta($post_id, "testimonial-message", $testimonial_message_value);
}
add_action("save_post", "save_testimonial_normal_meta_box", 10, 3);

function download_normal_meta_box_markup($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div class="custom-meta-box">
			<p class="download-inputfield">
				<input class="download-fileurl" id="download-fileurl" name="download-fileurl" type="text" value="<?php echo get_post_meta($object->ID, "download-fileurl", true); ?>" placeholder="<?php echo __('File Url', 'edunia');?>" readonly>
				<input class="download-filesize" id="download-filesize" name="download-filesize" type="text" value="<?php echo get_post_meta($object->ID, "download-filesize", true); ?>" placeholder="<?php echo __('Size', 'edunia');?>" readonly>
				<input class="download-filename" id="download-filename" name="download-filename" type="text" value="<?php echo get_post_meta($object->ID, "download-filename", true); ?>" placeholder="<?php echo __('Name', 'edunia');?>" readonly>
			</p>
			<p class="download-uploadbtn">
				<button class="button button-secondary button-large button-upload" id="upload-btn"><?php echo __('Select File', 'edunia');?></button>
			</p>
        </div>

<script type="text/javascript">
jQuery(document).ready(function($){
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var download_media = wp.media({ 
            title: $(this).text(),
            multiple: false
        }).open()
        .on('select', function(e){
            var media_uploaded = download_media.state().get('selection').first();
            $('#download-fileurl').val(media_uploaded.toJSON().url);
            $('#download-filesize').val(media_uploaded.toJSON().filesizeHumanReadable);
            $('#download-filename').val(media_uploaded.toJSON().filename);
        });
    });
});
</script>
    <?php  
}

function add_download_normal_meta_box() {
    add_meta_box("testimonial-meta-box-details", __('Media Upload', 'edunia'), "download_normal_meta_box_markup", "download", "normal", "high", null);
}
add_action("add_meta_boxes", "add_download_normal_meta_box");

function save_download_normal_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "download";
    if($slug != $post->post_type)
        return $post_id;
		$download_fileurl_value = "";
		$download_filesize_value = "";
		$download_filename_value = "";

    if(isset($_POST["download-fileurl"])) {
        $download_fileurl_value = $_POST["download-fileurl"];
    }   
    update_post_meta($post_id, "download-fileurl", $download_fileurl_value);

    if(isset($_POST["download-filesize"])) {
        $download_filesize_value = $_POST["download-filesize"];
    }   
    update_post_meta($post_id, "download-filesize", $download_filesize_value);

    if(isset($_POST["download-filename"])) {
        $download_filename_value = $_POST["download-filename"];
    }   
    update_post_meta($post_id, "download-filename", $download_filename_value);
}
add_action("save_post", "save_download_normal_meta_box", 10, 3);