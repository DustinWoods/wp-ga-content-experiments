<?php

Class WPgacxmaExperiment {

	//TODO: Internationalize label values, but keep properties static.
	//Define static properties
	public static $metricOptions = array(
      'adsenseAdsClicks'=>'Adsense Ads Clicks',
      'adsenseAdsViewed'=>'Adsense Ads Viewed',
      'adsenseRevenue'=>'Adsense Revenue',
      'bounces'=>'Bounces',
      'pageviews'=>'Page Views',
      'timeOnSite'=>'Time On Site',
      'transactions'=>'Transactions',
      'transactionRevenue'=>'Transaction Revenue',
      'goal1Completions'=>'Goal 1 Completions',
      'goal2Completions'=>'Goal 2 Completions',
      'goal3Completions'=>'Goal 3 Completions',
      'goal4Completions'=>'Goal 4 Completions',
      'goal5Completions'=>'Goal 5 Completions',
      'goal6Completions'=>'Goal 6 Completions',
      'goal7Completions'=>'Goal 7 Completions',
      'goal8Completions'=>'Goal 8 Completions',
      'goal9Completions'=>'Goal 9 Completions',
      'goal10Completions'=>'Goal 10 Completions',
      'goal11Completions'=>'Goal 11 Completions',
      'goal12Completions'=>'Goal 12 Completions',
      'goal13Completions'=>'Goal 13 Completions',
      'goal14Completions'=>'Goal 14 Completions',
      'goal15Completions'=>'Goal 15 Completions',
      'goal16Completions'=>'Goal 16 Completions',
      'goal17Completions'=>'Goal 17 Completions',
      'goal18Completions'=>'Goal 18 Completions',
      'goal19Completions'=>'Goal 19 Completions',
      'goal20Completions'=>'Goal 20 Completions'
	);

	public static $testingforOptions = array(
      'MAXIMUM'=>'Maximize',
      'MINIMUM'=>'Minimize'
    );

	public $experiment_post;

    public function __construct($experiment_id = null) {

    	if($experiment_id !== null) {
    		$this->$experiment_post = get_post($experiment_id);
    	}
    	
    }

    public function save() {

    }
}