<?php
/**
* The public Truth class
*/
if ( ! class_exists( 'Truth_Public' ) ) {

	/**
	* lets get started
	*/
	class Truth_Public {

		/**
		* Static property to hold our singleton instance
		* @var $instance
		*/
		static $instance = false;

		/**
		* Static property to hold our singleton instance
		* @var $instance
		*/
		public $options = array();

		/**
		* Indexes all book names
		* @var $instance
		*/
		public $index = array();

		/**
		* this is our constructor.
		* there are many like it, but this one is mine
		*/
		private function __construct() {

			$this->options = get_option( 'truth_settings' );
            $this->options['link_in_new_tab'] = isset( $this->options['link_in_new_tab'] ) ? $this->options['link_in_new_tab'] : 0;

			add_action( 'wp_footer', array( $this, 'insert_scripts' ) );

			if ( get_option('truth_authorization') && ( ! isset( $this->options['disable_auto_links'] ) || ! $this->options['disable_auto_links'] ) && 'youversion' == $this->options['engine'] ) {

				add_action( 'init', array( $this, 'create_index' ) );

				add_filter( 'the_content', array( $this, 'search_content' ), 99, 2 );
				add_filter( 'comment_text', array( $this, 'search_content' ), 99, 2 );
				add_filter( 'widget_text', array( $this, 'search_content' ), 99, 2 );

			}

			add_shortcode( 'truth', array( $this, 'shortcode' ) );

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
		* Register the JavaScript for the front-end.
		*
		* @since    0.0.1
		*/
		public function insert_scripts() {

			if ( 'biblesorg_highlighter' == $this->options['engine'] ) {

				$versionInfo = $this->get_bible_version(); ?>

            <script src="https://bibles.org/static/widget/v2/widget.js"></script>
            <script>
                GLOBALBIBLE.init({
                    url: "https://bibles.org",
                    bible: "<?php echo $versionInfo['id']; ?>",
                    autolink: <?php echo ( isset( $this->options['biblesorg_highlighter']['target_ids'] ) && '' != $this->options['biblesorg_highlighter']['target_ids'] ) ? '"' . $this->options['biblesorg_highlighter']['target_ids'] . '"' : '"body"'; ?>,
                });
            </script>

			<?php }

		}

		public function shortcode( $atts, $content = '' ) {

			if ( 'youversion' == $this->options['engine'] ) {

				extract( shortcode_atts( array(
					'version' => '',
				), $atts ) );

				$reference = $this->parse_verse( $content );
				$bibleVersion = $this->get_bible_version_from_abbr( strtolower( $version ) );

				$truth_url = $this->get_verse_url( $reference, $bibleVersion['id'] );

				if ( '' != $version && strpos( $truth_url, "/{$bibleVersion['id']}/" ) > 0 ) {
					switch ( $this->options['append_version'] ) {
						case 'none':
						break;
						case 'abbr':
						$content .= ', ' . strtoupper( $bibleVersion['abbr'] );
						break;
						case 'full':
						$content .= ', ' . $bibleVersion['name'];
						break;
						default:
						break;
					}
					$truth_target = $this->options['link_in_new_tab'] ? ' target="_blank"' : '';
					return '<a href="' . $truth_url . '" class="verseLink"' . $truth_target . '>' . $content . '</a>';
				} else {
					return $content;
				}

			} else {
				return $content;
			}

		}

		/**
		* Searches content for matches to reference regex
		* @param  string $content The content to be searched
		* @return string          The content after links have been added
		*/
		public function search_content( $content ) {

			$pattern = apply_filters( 'truth_regex_pattern', '/([0-3Ii]{0,3}|Song of|First|Second|Third|1st|2nd|3rd|Primero|Segundo|Tercero|Segunda|Tercera)[ ]{0,1}[\p{L}.]{1,20} \\d{1,3}(?!\\d):{0,1}[\\d,]*-{0,1}\\d{1,3}(?!\\d):{0,1}[\\d,]*/u' );

			preg_match_all( $pattern, $content, $matches, PREG_OFFSET_CAPTURE );

			$content = $this->insert_links( $content, $matches );

			return $content;

		}

		public function insert_links( $content, $matches ) {

			// Loop through matches, beginning at last match and working forward in order to use position of match for replacement in string.
			for ( $i = count( $matches[0] ) - 1; $i >= 0 ; $i-- ) {

				// Grab the matched string and position of the match
				$match = $matches[0][ $i ][0];
				$idx = $matches[0][ $i ][1];

				// Parse the match into [0] bookNumber, [1] chapterNumber, [2] verseNumber, [3] endChapterNumber, [4] endVerseNumber
				$reference = $this->parse_verse( $match );

				$truth_url = $this->get_verse_url( $reference );

				if ( $truth_url != '' ) {

					substr( $match, 0, 1) == ' ' ? $idx++ : FALSE;  //Make sure the $idx isn't thrown off when it trims
					$match = trim( $match );
					$before = substr( $content, 0, $idx );
					$after = substr( $content, $idx + strlen( $match ), strlen( $content ) - $idx - strlen( $match ) );
					$closeAnchor = strpos( $after, '</a' );
					$openAnchor = strpos( $after, '<a' );
					// is the following needed?
					$insertLink = FALSE;
					if ( $closeAnchor === FALSE && $openAnchor === FALSE ) {
						$insertLink = TRUE;
					} elseif ( $closeAnchor !== FALSE && $openAnchor !== FALSE && $closeAnchor > $openAnchor ) {
						$insertLink = TRUE;
					}

					if ( $insertLink === TRUE ) {
						$truth_target = $this->options['link_in_new_tab'] ? ' target="_blank"' : '';
						$content = $before . '<a href="' . $truth_url . '" class="verseLink"' . $truth_target . '>' . $match . "</a>" . $after;
					}

				}

			}

			return $content;

		}

		public function get_bible_version( $bibleVersionID = '' ) {

			$sources = Truth::get_sources();
			$site = $this->options['engine'];
			$source = $sources[ $site ];

			$bibleVersionID = '' == $bibleVersionID ? $this->options[ $site ]['bible_version'] : $bibleVersionID;

			foreach ( $source['versions'] as $languageGroup => $languageVersions ) {
				foreach ( $languageVersions as $versionID => $versionInfo ) {
					if ( $bibleVersionID == $versionID ) {
						return $versionInfo;
					}
				}
			}

		}

		public function get_bible_version_from_abbr( $abbr ) {

			$sources = Truth::get_sources();
			$site = $this->options['engine'];
			$versions = $sources[ $site ]['versions'];

			// $versionID = '' == $versionID ? $this->options[ $site ]['bible_version'] : $versionID;

			foreach ( $versions as $languageGroup => $languageVersions ) {
				foreach ( $languageVersions as $versionID => $versionInfo ) {
					if ( $abbr == $versionInfo['abbr'] ) {
						return $versionInfo;
					}
				}
			}

		}

		public function get_verse_url( $reference, $bibleVersionID = '' ) {

			if ( 0 == $reference['bookNumber'] ) return '';
			$sources = Truth::get_sources();
			$site = $this->options['engine'];
			$source = $sources[ $site ];

			$bookName = str_replace( $source['bookFind'], $source['bookReplace'], $source['books'][ $reference['bookNumber'] ] );

			if ( $reference['endVerseNumber'] > 0 ) {
				$result = $reference['endChapterNumber'] > 0 ? $source['URLChapterRange'] : $source['URLVerseRange'];
			} elseif ( $reference['verseNumber'] > 0 ) {
				$result = $source['URLSingleVerse'];
			} else {
				$result = $source['URLSingleChapter'];
			}

			$versionInfo = $this->get_bible_version( $bibleVersionID );

			return str_replace( array( '[book]', '[chapter]', '[verse]', '[endchapter]', '[endverse]', '[version]', '[id]' ), array( $bookName, $reference['chapterNumber'], $reference['verseNumber'], $reference['endChapterNumber'], $reference['endVerseNumber'], $versionInfo['abbr'], $versionInfo['id'] ), $result );

		}

		public function parse_verse( $match ) {

			$firstSplit = explode( ':', $match ); //separate chapter and verse

			// Get Book Part
			$idx = strrpos( $firstSplit[0], ' ' );
			$initialBookPart = substr( $firstSplit[0] , 0, $idx );

			$bookPart = str_replace( '.', '', $initialBookPart );
			$bookPart = strtolower( trim( $bookPart ) );

			$replacement_array = array(
				'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
				'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
				'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
				'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
				'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
				$bookPart = strtr( $bookPart, $replacement_array );

				$bookNumber = isset( $this->index[ $bookPart ] ) ? $this->index[ $bookPart ] : NULL;

				// Get Verses Part
				$versesPart = str_replace( $initialBookPart, '', $match );
				$versesPart = str_replace( ';', ',', $versesPart );

				$versesParts = explode( ',', $versesPart );


				$chapterNumber = (int) trim( substr( $firstSplit[0], $idx, strlen( $firstSplit[0] ) - $idx ) );

				$verseNumber = $endChapterNumber = $endVerseNumber = 0;

				$verseSplit = explode( '-', $firstSplit[1] );

				$verseNumber = $verseSplit[0];

				if ( count( $verseSplit ) > 1 ) {
					$endSplit = explode( ':', $verseSplit[1] );
					if (count( $endSplit ) == 1 ) {
						$endVerseNumber = $endSplit[0];
					} else {
						$endChapterNumber = (int) $endSplit[0];
						$endVerseNumber = (int) $endSplit[1];
					}
				}

				return array(
					'bookNumber'       => $bookNumber,
					'chapterNumber'    => $chapterNumber,
					'verseNumber'      => $verseNumber,
					'endChapterNumber' => $endChapterNumber,
					'endVerseNumber'   => $endVerseNumber
				);

			}


			public function create_index() {

				$books = array(
					array(),
					array('genesis','gen','ge','gn'),
					array('exodus','exo','ex','exod','exodo'),
					array('leviticus','lev','le','lv','levitico'),
					array('numbers','num', 'nu', 'nm', 'nb'),
					array('deuteronomy','deut','dt','deuteronomio'),
					array('joshua','josh','jos','jsh','josue'),
					array('judges','judg','jdg','jg','jdgs','jueces','jue','jc'),
					array('ruth','rth','ru'),
					array('1 samuel','1 sam','1 sa','1samuel','1s','i sa','1 sm','1sa','i sam','1sam','i samuel','1st samuel','first samuel'),
					array('2 samuel','2 sam','2 sa','2s','ii sa','2 sm','2sa','ii sam','2sam','ii samuel','2samuel','2nd samuel','second samuel'),
					array('1 kings','1 kgs','1 ki','1k','i kgs','1kgs','i ki','1ki','i kings','1kings','1st kgs','1st kings','first kings','first kgs','1kin','1 reyes','1 rey','1 re'),
					array('2 kings','2 kgs','2 ki','2k','ii kgs','2kgs','ii ki','2ki','ii kings','2kings','2nd kgs','2nd kings','second kings','second kgs','2kin','2 reyes','2 rey','2 re'),
					array('1 chronicles','1 chron','1 ch','i ch','1ch','1 chr','i chr','1chr','i chron','1chron','i chronicles','1chronicles','1st chronicles','first chronicles','1 cronicas','1 cro','1cr'),
					array('2 chronicles','2 chron','2 ch','ii ch','2ch','ii chr','2chr','ii chron','2chron','ii chronicles','2chronicles','2nd chronicles','second chronicles','2 cronicas','2 cro','2cr'),
					array('ezra','ezr','esdras','esd','es'),
					array('nehemiah','neh','ne','nehemias'),
					array('esther','esth','es','ester','est'),
					array('job','jb'),
					array('psalms','pslm','ps','psalm','psa','psm','pss','salmos','sal'),
					array('proverbs','prov','pr','prv','pro','proverbios'),
					array('ecclesiastes','eccles','ec','ecc','eclesiastes','ecl'),
					array('song of solomon','song','so','song of songs','sos','son','cantar de los cantares','cant'),
					array('isaiah','isa','is','isaias'),
					array('jeremiah','jer','je','jr','jeremias'),
					array('lamentations','lam','la','lamentaciones'),
					array('ezekiel','ezek','eze','ezk','ezequiel','ezeq','ez'),
					array('daniel','dan','da','dn'),
					array('hosea','hos','ho','oseas','os'),
					array('joel','joe','jl'),
					array('amos','am'),
					array('obadiah','obad','ob','abdias','abd'),
					array('jonah','jnh','jon','jonas'),
					array('micah','mic','miqueas','miq'),
					array('nahum','nah','na','nahun'),
					array('habakkuk','hab','habacuc'),
					array('zephaniah','zeph','zep','zp','sofonias','sof'),
					array('haggai','hag','hg','ageo','ag'),
					array('zechariah','zech','zec','zc','zacarias','zac','za'),
					array('malachi','mal','ml','malaquias'),
					array('matthew','matt','mt','mat','mateo'),
					array('mark','mrk','mk','mr','marcos','mc'),
					array('luke','luk','lk','lucas','luc','lc'),
					array('john','jn','jhn','juan'),
					array('acts','ac','hechos de los apostoles','hech','hch'),
					array('romans','rom','ro','rm','romanos'),
					array('1 corinthians','1 cor','1 co','i co','1co','i cor','1cor','i corinthians','1corinthians','1st corinthians','first corinthians','1 corin','1 corintios'),
					array('2 corinthians','2 cor','2 co','ii co','2co','ii cor','2cor','ii corinthians','2corinthians','2nd corinthians','second corinthians','2 corin','2 corintios'),
					array('galatians','gal','ga','galatas'),
					array('ephesians','ephes','eph','efesios','efes','ef'),
					array('philippians','phil','php','filipenses','fil','flp'),
					array('colossians','col','colosenses'),
					array('1 thessalonians','1 thess','1 th','i th','1th','i thes','1thes','i thess','1thess','i thessalonians','1thessalonians','1st thessalonians','first thessalonians','1ts','1 tesalonicenses'),
					array('2 thessalonians','2 thess','2 th','ii th','2th','ii thes','2thes','ii thess','2thess','ii thessalonians','2thessalonians','2nd thessalonians','second thessalonians','2ts','2 tesalonicenses'),
					array('1 timothy','1 tim','1 ti','i ti','1ti','i tim','1tim','i timothy','1timothy','1st timothy','first timothy','1 timoteo'),
					array('2 timothy','2 tim','2 ti','ii ti','2ti','ii tim','2tim','ii timothy','2timothy','2nd timothy','second timothy','2 timoteo'),
					array('titus','tit','tito'),
					array('philemon','philem','phm','filemon','filem','flm'),
					array('hebrews','heb','hebreos'),
					array('james','jas','jm','santiago','sant','stgo','st'),
					array('1 peter','1 pet','1 pe','i pe','1pe','i pet','1pet','i pt','1 pt','1pt','i peter','1peter','1st peter','first peter','1 pedro'),
					array('2 peter','2 pet','2 pe','ii pe','2pe','ii pet','2pet','ii pt','2 pt','2pt','ii peter','2peter','2nd peter','second peter','2 pedro'),
					array('1 john','1 jn','i jn','1jn','i jo','1jo','i joh','1joh','i jhn','1 jhn','1jhn','i john','1john','1st john','first john','1 juan'),
					array('2 john','2 jn','ii jn','2jn','ii jo','2jo','ii joh','2joh','ii jhn','2 jhn','2jhn','ii john','2john','2nd john','second john','2 juan'),
					array('3 john','3 jn','iii jn','3jn','iii jo','3jo','iii joh','3joh','iii jhn','3 jhn','3jhn','iii john','3john','3rd john','third john','3 juan'),
					array('jude','jud','judas','jds'),
					array('revelation','rev','re','apocalipsis','apocalipsis (de juan)','apoc','ap'),
				);

				// Re-key array to start at 1
				unset( $books[0] );

				foreach ( $books as $key => $names ) {
					foreach ( $names as $name ) {
						$this->index[ $name ] = $key;
					}
				}

			}

		}

	}
