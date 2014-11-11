<?php
Class WPgacxm_admin_metaBoxes {

  public static $instance;

  public function __construct() {
    if(isset(self::$instance)) {
      //Throw error! we only want one instance
    } else {
      self::$instance = $this;
    }
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

  function get_days_select($value = '3') {
    $html = '<input name="experiment-minimumdays" type="number" value="'.$value.'" min="3" max="90" />';
    return $this->get_option_edit_link($html,$value);
  }

  function get_traffic_slider($value = '1') {
    return $this->get_percent_slider($value);
  }

  function get_confidence_slider($value = '0.95') {
    return $this->get_percent_slider($value);
  }

  function get_percent_slider($value = '1') {
    $html = '<span class="range-display"></span><input name="input-range" type="range" value="'.$value.'" data-format="percent" step="0.005" min="0" max="1" />';
    return $this->get_option_edit_link($html,((float)$value*100)."%");
  }

  function get_testingfor_select($value = 'MINIMUM') {
    $options = array(
      'MAXIMUM'=>__('Maximum'),
      'MINIMUM'=>__('Minimum')
    );
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
    $options = array(
      'adsenseAdsClicks'=>__('Adsense Ads Clicks'),
      'adsenseAdsViewed'=>__('Adsense Ads Viewed'),
      'adsenseRevenue'=>__('Adsense Revenue'),
      'bounces'=>__('Bounces'),
      'pageviews'=>__('Page Views'),
      'timeOnSite'=>__('Time On Site'),
      'transactions'=>__('transactions'),
      'transactionRevenue'=>__('Transaction Revenue'),
      'goal1Completions'=>__('Goal 1 Completions'),
      'goal2Completions'=>__('Goal 2 Completions'),
      'goal3Completions'=>__('Goal 3 Completions'),
      'goal4Completions'=>__('Goal 4 Completions'),
      'goal5Completions'=>__('Goal 5 Completions'),
      'goal6Completions'=>__('Goal 6 Completions'),
      'goal7Completions'=>__('Goal 7 Completions'),
      'goal8Completions'=>__('Goal 8 Completions'),
      'goal9Completions'=>__('Goal 9 Completions'),
      'goal10Completions'=>__('Goal 10 Completions'),
      'goal11Completions'=>__('Goal 11 Completions'),
      'goal12Completions'=>__('Goal 12 Completions'),
      'goal13Completions'=>__('Goal 13 Completions'),
      'goal14Completions'=>__('Goal 14 Completions'),
      'goal15Completions'=>__('Goal 15 Completions'),
      'goal16Completions'=>__('Goal 16 Completions'),
      'goal17Completions'=>__('Goal 17 Completions'),
      'goal18Completions'=>__('Goal 18 Completions'),
      'goal19Completions'=>__('Goal 19 Completions'),
      'goal20Completions'=>__('Goal 20 Completions')
    );
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

    $post = WPgacxma::$instance->get_experiment_post($editing_post->ID);

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
      <div class="misc-pub-section advanced-experimnt-prop"><label class="selectit"><input value="1" type="checkbox" name="experiment_equal_weight" checked=""><?php _e('Equal Traffic Weight'); ?></label></div>
      <div class="misc-pub-section"><?php printf(__('Experiment must run for at least %s days.'),$this->get_days_select()); ?></div>
      <div class="misc-pub-section"><?php printf(__('Testing for %s %s.'),$this->get_testingfor_select(),$this->get_metric_select()); ?></div>
      <div class="misc-pub-section"><?php printf(__('Experiment on %s of traffic.'),$this->get_traffic_slider());  ?></div>
      <div class="misc-pub-section"><?php printf(__('Choose winner with %s confidence.'),$this->get_confidence_slider());  ?></div>
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