<?php
Class WPgacxm_admin_metaBoxes {

  private static $instance;

  private function __construct() {

  }

  public static function get_instance() {

    if(!isset(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
    
  }  

  //creates a text link describing the value
  //When link is clicked, input field is shown
  function get_option_edit_link($inputhtml,$value,$class = '') {
    $html = '<div class="edit-experiment-prop '.$class.'">';
    $html .= '<a href="#" class="edit-link"><i class="dashicons dashicons-edit"></i><span>'.$value.'</span></a>';
    $html .= '<div class="edit-area">'.$inputhtml.'</div>';
    $html .= '</div>';
    return $html;
  }

  function get_email_input($value = 'admin@domain.com') {
    $html = '<input name="experiment-email" type="email" value="'.$value.'" />';
    return $this->get_option_edit_link($html,$value);
  }

  function get_days_select($value = '3') {
    $html = '<input name="experiment-minimumdays" type="number" value="'.$value.'" min="3" max="90" />';
    return $this->get_option_edit_link($html,$value);
  }

  function get_traffic_slider($value = '1') {
    return $this->get_percent_slider($value);
  }

  function get_confidence_options($value = '0.95') {
    $options = array(
      '0.95'=>'95.0%',
      '0.96'=>'96.0%',
      '0.97'=>'97.0%',
      '0.98'=>'98.0%',
      '0.99'=>'99.0%',
      '0.995'=>'99.5%');
    $html = "<select name='experiment-confidence'>";
    $html .= $this->get_options_elements($options,$value);
    $html .= "</select>";
    return $this->get_option_edit_link($html,$options[$value]);
  }

  function get_percent_slider($value = '1') {
    $valuestr = ((float)$value*100)."%";
    $html = '<input name="experiment-range" type="range" value="'.$value.'" data-format="percent" step="0.01" min="0" max="1" /><span class="range-display">'.$valuestr.'</span>';
    return $this->get_option_edit_link($html,$valuestr);
  }

  function get_testingfor_select($value = 'MINIMUM') {
    $options = WPgacxmaExperiment::$testingforOptions;
    $html = "<select name='experiment-testingfor'>";
    $html .= $this->get_options_elements($options,$value);
    $html .= "</select>";
    return $this->get_option_edit_link($html,$options[$value]);
  }

  function get_options_elements($options,$selected) {
    $html = '';
    foreach ($options as $value => $label) {
      $html .= "<option ".($selected == $value ? 'selected' : '')." value='$value'>$label</option>";
    }
    return $html;
  }

  function get_metric_select($value = 'bounces') {
    $options = WPgacxmaExperiment::$metricOptions;
    $html = "<select name='experiment-metric'>";
    $html .= $this->get_options_elements($options,$value);
    $html .= "</select>";
    return $this->get_option_edit_link($html,$options[$value]);
  }

  function get_status_option($value = 'draft') {

    //choosing status is a bit complicated...
    switch ($value) {
      case 'running':
        $options = array(
          'running'=>'Running',
          'ended'=>'Stop'
        );
        break;
      
      case 'ended':
        $options = array(
          'ended'=>'Ended'
        );
        break;

      default:
      case 'ready_to_run':
      case 'draft':
        $options = array(
          'draft'=>'Draft',
          'ready_to_run'=>'Ready',
          'running'=>'Run'
        );
        break;
    }

    $html = "<select name='experiment-status'>";
    $html .= $this->get_options_elements($options,$value);
    $html .= "</select>";
    return $this->get_option_edit_link($html,$options[$value]);
  }


  //Renders content for meta box on post
  function admin_post_meta_box($editing_post, $args = array()) {

    $post = WPgacxma::get_instance()->get_current_experiment_post($editing_post->ID);



    if($post === false) {
      //No experiment
      ?>
      <a href="" class="create-experiment hide-if-no-js button"><?php _e('Create Experiment', 'wpgacxm'); ?></a>
      <?php
      return;
    }
    ?>
    <div class="misc-experiment-props">
      <div class="misc-pub-section advanced-experimnt-prop"><label for=""><?php _e('Title'); ?>:</label><input type="text" id="" name="" size="16" autocomplete="off" value="<?php echo htmlentities($editing_post->post_title); ?>"></div>
      <div class="misc-pub-section advanced-experimnt-prop"><label for="experiment-description"><?php _e('Hypothesis'); ?>:</label><textarea name="experiment-description" placeholder="<?php _e('Describe the experiment'); ?>"><?php echo $editing_post->post_type." ".htmlentities($editing_post->post_title); ?></textarea></div>
      <div class="misc-pub-section advanced-experimnt-prop"><label class="selectit"><input value="1" type="checkbox" name="experiment_equal_weight" checked=""><?php _e('Distribute traffic evenly across all variations.'); ?></label></div>
      <div class="misc-pub-section"><?php printf(__('Experiment must run for at least %s days.'),$this->get_days_select()); ?></div>
      <div class="misc-pub-section"><?php printf(__('Testing to %s %s.'),$this->get_testingfor_select(),$this->get_metric_select()); ?></div>
      <div class="misc-pub-section"><?php printf(__('Experiment on %s of traffic.'),$this->get_traffic_slider());  ?></div>
      <div class="misc-pub-section"><?php printf(__('Choose winner with %s confidence.'),$this->get_confidence_options());  ?></div>
      <div class="misc-pub-section"><label class="selectit"><input value="1" type="checkbox" name="experiment_use_winner" checked=""><?php printf(__('Publish winning %1$s when experiment has ended.'),strtolower(get_post_type_labels(get_post_type_object($editing_post->post_type))->singular_name)); ?></label></div>
      <div class="misc-pub-section"><label class="selectit"><input value="1" type="checkbox" name="experiment_trash_loser" checked=""><?php printf(__('Move losing %1$s to trash.'),strtolower(get_post_type_labels(get_post_type_object($editing_post->post_type))->name)); ?></label></div>
      <div class="misc-pub-section"><label class="selectit"><input value="1" type="checkbox" name="experiment_email_setting" checked=""><?php printf(__('Send email to %s when experiment has ended.'),$this->get_email_input()); ?></label></div>
      <div class="misc-pub-section"><?php printf(__('Status: %s'),$this->get_status_option());  ?></div>
    </div>

    <div id="major-experiment-actions">
    <div id="delete-action">
    <a class="deletion" href=""><?php _e('Delete Experiment'); ?></a></div>

    <div id="experiment-action">
      <span class="spinner"></span>
      <a class="button button-primary button-large" id="publish_experiment"><?php _e('Update'); ?></a>
    </div>
    <div class="clear"></div>
    </div>    
    <?php
  }
}