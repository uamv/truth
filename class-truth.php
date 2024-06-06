<?php
/**
* The core Truth class
*/
if ( ! class_exists( 'Truth' ) ) {

	/**
	* lets get started
	*/
	class Truth {

		/**
		* Static property to hold our singleton instance
		* @var $instance
		*/
		static $instance = false;

		/**
		* this is our constructor.
		* there are many like it, but this one is mine
		*/
		private function __construct() {

			if ( is_admin() ) {
				require_once( TRUTH_DIR . 'class-truth-admin.php' );
				Truth_Admin::get_instance();
			} else {
				require_once( TRUTH_DIR . 'class-truth-public.php' );
				Truth_Public::get_instance();
			}

		}

		/**
		* If an instance exists, this returns it.  If not, it creates one and
		* returns it.
		*
		* @return $instance
		*/
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		* Return supported version for Truth plugin
		* @return [type] [description]
		*/
		public static function get_sources() {

			$sources = array(
				'biblesorg_highlighter' => array(
					'versions' => array(
						'eng' => array(
							'AMP' => array( 'name' => 'Amplified Bible', 'abbr' => 'AMP', 'id' => 'a81b73293d3080c9-01' ),
							'CEV' => array( 'name' => 'Contemporary English Version', 'abbr' => 'CEV', 'id' => '555fef9a6cb31151-01' ),
							'CEV2012' => array( 'name' => 'Contemporary English Version (Anglicised) 2012', 'abbr' => 'CEV', 'id' => '108aa0495c0f45fb-01' ),
							'ESV' => array( 'name' => 'English Standard Version', 'abbr' => 'ESV', 'id' => 'f421fe261da7624f-01' ),
							'GNTD' => array( 'name' => 'Good News Translation (US Version)', 'abbr' => 'GNTD', 'id' => '61fd76eafa1577c2-01' ),
							'KJV' => array( 'name' => 'King James Version, American Edition', 'abbr' => 'KJV', 'id' => 'a6aee10bb058511c-02' ),
							'KJVA' => array( 'name' => 'King James Version with Apocrypha, American Edition', 'abbr' => 'KJVA', 'id' => 'a6aee10bb058511c-01' ),
							'NASB' => array( 'name' => 'New American Standard Bible', 'abbr' => 'NASB', 'id' => 'b8ee27bcd1cae43a-01' ),
							'NIV' => array( 'name' => 'New International Version', 'abbr' => 'NIV', 'id' => '78a9f6124f344018-01' ),
							'NRSV' => array( 'name' => 'New Revised Standard Version', 'abbr' => 'NRSV', 'id' => '1fd99b0d5841e19b-02' ),
                     'GNB' => array( 'name' => 'Good News Bible (Anglicised) 1994', 'abbr' => 'GNB', 'id' => 'de08848bef5aa286-02' ),
                     'GNBC' => array( 'name' => 'Good News Bible (Anglicised) Catholic Edition 1994', 'abbr' => 'GNB', 'id' => 'de08848bef5aa286-03' ),
						)
					)
				),
				'youversion' => array(
					'URLSingleVerse'  => 'https://my.bible.com/bible/[id]/[book].[chapter].[verse]',
					'URLVerseRange'   => 'https://my.bible.com/bible/[id]/[book].[chapter].[verse]-[endverse]',
					'URLSingleChapter'=> 'https://my.bible.com/bible/[id]/[book].[chapter]',
					'URLChapterRange' => '',
					'bookFind'        => array( 'song_of_solomon', ' ' ),
					'bookReplace'     => array( 'song', ''),
					'books'           => array( '', 'gen', 'exo', 'lev', 'num', 'deu', 'jos', 'jdg', 'rut', '1sa', '2sa', '1ki', '2ki', '1ch', '2ch', 'ezr', 'neh', 'est', 'job', 'psa', 'pro', 'ecc', 'sng', 'isa', 'jer', 'lam', 'ezk', 'dan', 'hos', 'jol', 'amo', 'oba', 'jon', 'mic', 'nam', 'hab', 'zep', 'hag', 'zec', 'mal', 'mat', 'mrk', 'luk', 'jhn', 'act', 'rom', '1co', '2co', 'gal', 'eph', 'php', 'col', '1th', '2th', '1ti', '2ti', 'tit', 'phm', 'heb', 'jas', '1pe', '2pe', '1jn', '2jn', '3jn', 'jud', 'rev' ),
					'versions'        => array(
						'English' => array(
							'8' => array( 'name' => 'Amplified Bible (AMP)', 'abbr' => 'amp', 'id' => '8' ),
							'12' => array( 'name' => 'American Standard Version', 'abbr' => 'asv', 'id' => '12' ),
							'3034' => array( 'name' => 'Berean Study Bible', 'abbr' => 'bsb', 'id' => '3034' ),
							'37' => array( 'name' => 'Common English Bible', 'abbr' => 'ceb', 'id' => '37' ),
							'392' => array( 'name' => 'Contemporary English Version', 'abbr' => 'cevus06', 'id' => '392' ),
							'42' => array( 'name' => 'Catholic Public Domain Version', 'abbr' => 'cpdv', 'id' => '42' ),
							'478' => array( 'name' => 'Darby Translation 1890', 'abbr' => 'darby', 'id' => '478' ),
							'55' => array( 'name' => 'Douay Rheims', 'abbr' => 'dra', 'id' => '55' ),
							'59' => array( 'name' => 'English Standard Version', 'abbr' => 'esv', 'id' => '59' ),
							'416' => array( 'name' => 'Good News Bible', 'abbr' => 'gnbdc', 'id' => '416' ),
							'70' => array( 'name' => 'GOD\'S WORD Translation', 'abbr' => 'gwt', 'id' => '70' ),
							'68' => array( 'name' => 'Good News Translation', 'abbr' => 'gnt', 'id' => '68' ),
							'72' => array( 'name' => 'Holman Christian Standard Bible', 'abbr' => 'hcsb', 'id' => '72' ),
							'1' => array( 'name' => 'King James Version', 'abbr' => 'kjv', 'id' => '1' ),
							'97' => array( 'name' => 'The Message', 'abbr' => 'msg', 'id' => '97' ),
							'100' => array( 'name' => 'New American Standard Bible', 'abbr' => 'nasb', 'id' => '100' ),
							'105' => array( 'name' => 'New Century Version', 'abbr' => 'ncv', 'id' => '105' ),
							'107' => array( 'name' => 'New English Translation', 'abbr' => 'net', 'id' => '107' ),
							'110' => array( 'name' => 'New International Reader\'s Version', 'abbr' => 'nirv', 'id' => '110' ),
							'111' => array( 'name' => 'New International Version', 'abbr' => 'niv', 'id' => '111' ),
							'114' => array( 'name' => 'New King James Version', 'abbr' => 'nkjv', 'id' => '114' ),
							'116' => array( 'name' => 'New Living Translation', 'abbr' => 'nlt', 'id' => '116' ),
							'2016' => array( 'name' => 'New Revised Standard Version', 'abbr' => 'nrsv', 'id' => '2016' ),
							'130' => array( 'name' => 'Orthodox Jewish Bible', 'abbr' => 'ojb', 'id' => '130' ),
							'314' => array( 'name' => 'Tree of Life Bible', 'abbr' => 'tlv', 'id' => '314' ),
							'206' => array( 'name' => 'World English Bible', 'abbr' => 'web', 'id' => '206' ),
						),
						'Español' => array(
							'28' => array( 'name' => 'La Palabra', 'abbr' => 'blph', 'id' => '28'),
							'89' => array( 'name' => 'La Bilia de las Americas', 'abbr' => 'lbla', 'id' => '89' ),
							'128' => array( 'name' => 'Nueva Versión Internacional', 'abbr' => 'nvi', 'id' => '128' ),
							'149' => array( 'name' => 'Biblia Reina Valera 1960', 'abbr' => 'rvr60', 'id' => '149' ),
						),
					)
				)
			);

			return $sources;

		}

	}

}
