<?php
/**
 * BaseTemplate class for the HasSomeColours skin
 *
 * @ingroup Skins
 */
class HasSomeColoursTemplate extends BaseTemplate {
	/**
	 * Outputs the entire contents of the page
	 */
	public function execute() {
		$html = '';
		$html .= $this->get( 'headelement' );

		$html .= Html::openElement( 'div', [ 'id' => 'mw-wrapper' ] );

		$html .= Html::openElement( 'div', [ 'id' => 'mw-header' ] );
		$html .= Html::openElement( 'div', [ 'class' => 'main-column' ] );
		$html .= Html::rawElement( 'div', [ 'id' => 'mw-navigation' ],
			Html::rawElement(
				'h2',
				[],
				$this->getMsg( 'navigation-heading' )->parse()
			) .
			// User profile links
			Html::rawElement(
				'div',
				[ 'id' => 'user-tools' ],
				$this->getUserLinks()
			) .
			// Global navigation
			Html::rawElement(
				'div',
				[ 'id' => 'global-navigation' ],
				$this->getGlobalLinks()
			) .
			$this->getClear()
		);
		$html .= Html::closeElement( 'div' );
		$html .= Html::closeElement( 'div' );

		$html .= Html::openElement( 'div', [ 'id' => 'mw-content-header' ] );
		$html .= Html::rawElement( 'div', [ 'class' => 'main-column' ],
			$this->getSearch() .
			$this->getLogo() .
			$this->getClear() .
			$this->getIfExists( 'sitenotice', [
				'wrapper' => 'div',
				'parameters' => [ 'id' => 'siteNotice' ]
			] ) .
			$this->getIfExists( 'newtalk', [
				'wrapper' => 'div',
				'parameters' => [ 'class' => 'usermessage' ]
			] ) .
			$this->getClear()
		);
		$html .= Html::closeElement( 'div' );

		$html .= Html::openElement( 'div', [ 'id' => 'mw-content' ] );
		$html .= Html::openElement( 'div', [ 'class' => 'main-column' ] );

		// Site navigation/sidebar

		$html .= Html::openElement( 'div', [ 'id' => 'site-navigation' ] );
		$html .= Html::rawElement( 'div', [ 'id' => 'mw-sidebar' ], $this->getSiteNavigation() );
		// Toolbox
		$html .= $this->getPortlet( 'tb', $this->data['sidebar']['TOOLBOX'], 'toolbox' );
		// Languages
		if ( $this->data['language_urls'] !== false ) {
			$html .= $this->getPortlet( 'lang', $this->data['language_urls'], 'otherlanguages' );
		}
		$html .= Html::closeElement( 'div' );

		// Page editing and tools
		$html .= Html::openElement( 'div', [ 'id' => 'page-block' ] );
		$html .= Html::rawElement(
			'div',
			[ 'id' => 'page-tools' ],
			$this->getPageLinks()
		);
		$html .= Html::rawElement( 'div', [ 'id' => 'content', 'class' => 'mw-body', 'role' => 'main' ],
			$this->getIndicators() .
			Html::rawElement( 'h1',
				[
					'class' => 'firstHeading',
					'lang' => $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode()
				],
				$this->get( 'title' )
			) .
			Html::rawElement( 'div', [ 'id' => 'siteSub' ],
				$this->getMsg( 'tagline' )->parse()
			) .
			Html::rawElement( 'div', [ 'class' => 'mw-body-content' ],
				Html::rawElement( 'div', [ 'id' => 'contentSub' ],
					$this->getIfExists( 'subtitle', [ 'wrapper' => 'p' ] ) .
					Html::rawElement( 'p', [], $this->get( 'undelete' ) )
				) .
				$this->get( 'bodycontent' ) .
				$this->getClear() .
				Html::rawElement( 'div', [ 'class' => 'printfooter' ],
					$this->get( 'printfooter' )
				) .
				$this->getIfExists( 'catlinks' ) .
				$this->getIfExists( 'dataAfterContent' ) .
				$this->get( 'debughtml' )
			)
		);
		$html .= Html::closeElement( 'div' );
		$html .= Html::closeElement( 'div' );
		$html .= Html::closeElement( 'div' );

		$html .= Html::openElement( 'div', [ 'id' => 'mw-footer' ] );
		$html .= Html::rawElement( 'div', [ 'class' => 'main-column' ],
			$this->getFooterBlock()
		);
		$html .= Html::closeElement( 'div' );

		$html .= Html::closeElement( 'div' );
		$html .= $this->getTrail();
		$html .= Html::closeElement( 'body' );
		$html .= Html::closeElement( 'html' );

		echo $html;
	}

	/**
	 * Generates the logo and (optionally) site title
	 *
	 * @param string $id
	 * @param bool $imageOnly
	 * @return string html
	 */
	protected function getLogo( $id = 'p-logo', $imageOnly = false ) {
		$config = $this->getSkin()->getContext()->getConfig();
		$logos = ResourceLoaderSkinModule::getAvailableLogos( $config );
		if ( isset( $logos['wordmark'] ) ) {
			$wordmarkData = $logos['wordmark'];
			$wordmark = Html::element( 'img', [
				'src' => $wordmarkData['src'],
				'height' => $wordmarkData['height'] ?? null,
				'width' => $wordmarkData['width'] ?? null,
			] );
		} else {
			$wordmark = Html::element( 'h1', [ 'class' => 'wordmark-text' ], $this->getMsg( 'sitetitle' )->text() );
		}

		$html = Html::openElement(
			'div', [ 'id' => $id, 'class' => 'mw-portlet', 'role' => 'banner' ]
		);
		$html .= Html::element(
			'a',
			[
				'href' => $this->data['nav_urls']['mainpage']['href'],
				'class' => 'mw-wiki-logo',
			] + Linker::tooltipAndAccesskeyAttribs( 'p-logo' )
		);
		if ( !$imageOnly ) {
			$html .= Html::rawElement(
				'a',
				[
					'id' => 'p-banner',
					'class' => 'mw-wiki-title',
					'href' => $this->data['nav_urls']['mainpage']['href']
				] + Linker::tooltipAndAccesskeyAttribs( 'p-logo' ),
				$wordmark
			);
		}
		$html .= Html::closeElement( 'div' );

		return $html;
	}

	/**
	 * Generates the search form
	 * @return string html
	 */
	protected function getSearch() {
		$html = Html::openElement(
			'form',
			[
				'action' => $this->get( 'wgScript' ),
				'role' => 'search',
				'class' => 'mw-portlet',
				'id' => 'p-search'
			]
		);
		$html .= Html::hidden( 'title', $this->get( 'searchtitle' ) );
		$html .= Html::rawElement(
			'h3',
			[],
			Html::label( $this->getMsg( 'search' )->escaped(), 'searchInput' )
		);
		$html .= $this->makeSearchInput( [ 'id' => 'searchInput' ] );
		$html .= $this->makeSearchButton( 'go', [ 'id' => 'searchGoButton', 'class' => 'searchButton' ] );
		$html .= Html::closeElement( 'form' );

		return $html;
	}

	/**
	 * Generates the sidebar
	 * Set the elements to true to allow them to be part of the sidebar
	 * Or get rid of this entirely, and take the specific bits to use wherever you actually want them
	 *  * Toolbox is the page/site tools that appears under the sidebar in vector
	 *  * Languages is the interlanguage links on the page via en:... es:... etc
	 *  * Default is each user-specified box as defined on MediaWiki:Sidebar; you will still need a foreach loop
	 *    to parse these.
	 * @return string html
	 */
	protected function getSiteNavigation() {
		$html = '';

		$sidebar = $this->getSidebar();

		// Do these elsewhere
		$sidebar['SEARCH'] = false;
		$sidebar['TOOLBOX'] = false;
		$sidebar['LANGUAGES'] = false;

		foreach ( $sidebar as $name => $content ) {
			if ( $content === false ) {
				continue;
			}
			// Numeric strings gets an integer when set as key, cast back - T73639
			$name = (string)$name;

			$html .= $this->getPortlet( $name, $content['content'] );
		}
		return $html;
	}

	/**
	 * Print arbitrary block of navigation
	 * Message parsing is limited to first 15 lines only for this skin.
	 *
	 * @param string $linksMessage
	 * @param string $id
	 * @param bool $doubleHeader Stupid mobile hack
	 *
	 * @return string html
	 */
	protected function getNavigation( $linksMessage, $id, $doubleHeader = false ) {
		$message = trim( $this->getMsg( $linksMessage )->inContentLanguage()->text() );
		$lines = array_slice( explode( "\n", $message ), 0, 15 );
		$links = [];
		foreach ( $lines as $line ) {
			// ignore empty lines
			if ( strlen( $line ) == 0 ) {
				continue;
			}

			$item = [];

			$line_temp = explode( '|', trim( $line, '* ' ), 3 );
			if ( count( $line_temp ) > 1 ) {
				$line = $line_temp[1];
				$link = $this->getMsg( $line_temp[0] )->inContentLanguage()->text();

				// Pull out third item as a class
				if ( count( $line_temp ) == 3 ) {
					$item['class'] = Sanitizer::escapeIdForAttribute( $line_temp[2] );
				}
			} else {
				$line = $line_temp[0];
				$link = $line_temp[0];
			}
			$item['id'] = Sanitizer::escapeIdForAttribute( $line );

			// Determine what to show as the human-readable link description
			if ( $this->getMsg( $line )->isDisabled() ) {
				// It's *not* the name of a MediaWiki message, so display it as-is
				$item['text'] = $line;
			} else {
				// Guess what -- it /is/ a MediaWiki message!
				$item['text'] = $this->getMsg( $line )->text();
			}

			$href = '#';
			if ( $link !== null ) {
				if ( $this->getMsg( $line_temp[0] )->isDisabled() ) {
					$link = $line_temp[0];
				}

				$href = Skin::makeInternalOrExternalUrl( $link );
			}
			$item['href'] = $href;

			$links[] = $item;
		}

		return $this->getPortlet( $id, $links, null, [ 'extra-header' => $doubleHeader, 'incontentlanguage' => true ] );
	}

	/**
	 * Menu for global navigation (for cross-wiki stuff or just whatever things)
	 *
	 * @return string html
	 */
	protected function getGlobalLinks() {
		$html = '';
		if ( !$this->getMsg( 'global-links-menu' )->inContentLanguage()->isDisabled() ) {
			$html = $this->getNavigation( 'global-links-menu', 'global-links' );
		}

		return $html;
	}

	/**
	 * Generates page-related tools/links
	 * You will probably want to split this up and move all of these to somewhere that makes sense for your skin.
	 * @return string html
	 */
	protected function getPageLinks() {
		// Namespaces: links for 'content' and 'talk' for namespaces with talkpages. Otherwise is just the content.
		// Usually rendered as tabs on the top of the page.
		$html = $this->getPortlet(
			'namespaces',
			$this->data['content_navigation']['namespaces']
		);
		// Variants: Language variants. Displays list for converting between different scripts in the same language,
		// if using a language where this is applicable.
		$html .= $this->getPortlet(
			'variants',
			$this->data['content_navigation']['variants'],
			null,
			[ 'extra-classes' => 'tool-dropdown' ]
		);
		// Other actions for the page: move, delete, protect, everything else
		$html .= $this->getPortlet(
			'actions',
			$this->data['content_navigation']['actions'],
			null,
			[ 'extra-classes' => 'tool-dropdown' ]
		);
		// 'View' actions for the page: view, edit, view history, etc
		$html .= $this->getPortlet(
			'views',
			$this->data['content_navigation']['views']
		);

		return $html;
	}

	/**
	 * Generates user tools menu
	 * @return string html
	 */
	protected function getUserLinks() {
		return $this->getPortlet(
			'personal',
			$this->getPersonalTools(),
			'personaltools'
		);
	}

	/**
	 * Simple wrapper for random if-statement-wrapped $this->data things
	 *
	 * @param string $object name of thing
	 * @param array $setOptions
	 *
	 * @return string html
	 */
	protected function getIfExists( $object, $setOptions = [] ) {
		$options = $setOptions + [
			'wrapper' => 'none',
			'parameters' => []
		];

		$html = '';

		if ( $this->data[$object] ) {
			if ( $options['wrapper'] == 'none' ) {
				$html .= $this->get( $object );
			} else {
				$html .= Html::rawElement(
					$options['wrapper'],
					$options['parameters'],
					$this->get( $object )
				);
			}
		}

		return $html;
	}

	/**
	 * Generate a block of navigation links with a header
	 *
	 * <INSERT SCREAMING>
	 *
	 * @param string $name
	 * @param array|string $content array of links for use with makeListItem, or a block of text
	 * @param null|string|array $msg
	 * @param array $setOptions random crap to rename/do/whatever
	 *
	 * @return string HTML
	 */
	protected function getPortlet( $name, $content, $msg = null, $setOptions = [] ) {
		// random stuff to override with any provided options
		$options = $setOptions + [
			// extra classes/ids
			'id' => 'p-' . $name,
			'class' => [ 'mw-portlet', 'emptyPortlet' => !$content ],
			'extra-classes' => [],
			// what to wrap the body list in, if anything
			'body-wrapper' => 'div',
			'body-id' => '',
			'body-class' => 'mw-portlet-body',
			'body-extra-classes' => [],
			// makeListItem options
			'list-item' => [ 'text-wrapper' => [ 'tag' => 'span' ] ],
			// option to stick arbitrary stuff at the beginning of the ul
			'list-prepend' => '',
			'extra-header' => false,
			'incontentlanguage' => false
		];

		// Handle the different $msg possibilities
		if ( $msg === null ) {
			$msg = $name;
		} elseif ( is_array( $msg ) ) {
			$msgString = array_shift( $msg );
			$msgParams = $msg;
			$msg = $msgString;
		}
		if ( $options['incontentlanguage'] ) {
			$msgObj = $this->getMsg( $msg )->inContentLanguage();
		} else {
			$msgObj = $this->getMsg( $msg );
		}
		if ( $msgObj->exists() ) {
			if ( isset( $msgParams ) && !empty( $msgParams ) ) {
				$msgString = $this->getMsg( $msg, $msgParams )->parse();
			} else {
				$msgString = $msgObj->parse();
			}
		} else {
			$msgString = htmlspecialchars( $msg );
		}

		$labelId = Sanitizer::escapeIdForAttribute( "p-$name-label" );

		if ( is_array( $content ) ) {
			if ( !count( $content ) ) {
				return '';
			}

			$contentText = '';
			if ( $options['extra-header'] ) {
				$contentText .= Html::rawElement( 'h3', [], $msgString );
			}

			$contentText .= Html::openElement( 'ul',
				[ 'lang' => $this->get( 'userlang' ), 'dir' => $this->get( 'dir' ) ]
			);
			$contentText .= $options['list-prepend'];
			foreach ( $content as $key => $item ) {
				$contentText .= $this->makeListItem( $key, $item, $options['list-item'] );
			}
			$contentText .= Html::closeElement( 'ul' );
		} else {
			$contentText = $content;
		}

		// Special handling for role=search and other weird things
		$divOptions = [
			'role' => 'navigation',
			'class' => $this->mergeClasses( $options['class'], $options['extra-classes'] ),
			'id' => Sanitizer::escapeIdForAttribute( $options['id'] ),
			'title' => Linker::titleAttrib( $options['id'] ),
			'aria-labelledby' => $labelId,
		];

		$labelOptions = [
			'id' => $labelId,
			'lang' => $this->get( 'userlang' ),
			'dir' => $this->get( 'dir' )
		];

		// @phan-suppress-next-line PhanSuspiciousValueComparison
		if ( $options['body-wrapper'] !== 'none' ) {
			$bodyDivOptions = [ 'class' => $this->mergeClasses(
				$options['body-class'], $options['body-extra-classes']
			) ];
			if ( strlen( $options['body-id'] ) ) {
				$bodyDivOptions['id'] = $options['body-id'];
			}
			$body = Html::rawElement( $options['body-wrapper'], $bodyDivOptions,
				$contentText .
				$this->getAfterPortlet( $name )
			);
		} else {
			$body = $contentText . $this->getAfterPortlet( $name );
		}

		$html = Html::rawElement( 'div', $divOptions,
			Html::rawElement( 'h3', $labelOptions, $msgString ) .
			$body
		);

		return $html;
	}

	/**
	 * Helper function for getPortlet
	 *
	 * Merge all provided css classes into a single array
	 * Account for possible different input methods matching what Html::element stuff takes
	 *
	 * @param string|array $class base portlet/body class
	 * @param string|array $extraClasses any extra classes to also include
	 *
	 * @return array all classes to apply
	 */
	protected function mergeClasses( $class, $extraClasses ) {
		if ( !is_array( $class ) ) {
			$class = [ $class ];
		}
		if ( !is_array( $extraClasses ) ) {
			$extraClasses = [ $extraClasses ];
		}

		return array_merge( $class, $extraClasses );
	}

	/**
	 * Wrapper to catch output of old hooks expecting to write directly to page
	 * We no longer do things that way.
	 *
	 * @param string $hook event
	 * @param array $hookOptions args
	 *
	 * @return string html
	 */
	protected function deprecatedHookHack( $hook, $hookOptions = [] ) {
		$hookContents = '';
		ob_start();
		Hooks::run( $hook, $hookOptions );
		$hookContents = ob_get_contents();
		ob_end_clean();
		if ( !trim( $hookContents ) ) {
			$hookContents = '';
		}

		return $hookContents;
	}

	/**
	 * Better renderer for getFooterIcons and getFooterLinks
	 *
	 * @param array $setOptions Miscellaneous other options
	 * * 'id' for footer id
	 * * 'class' for footer class
	 * * 'order' to determine whether icons or links appear first: 'iconsfirst' or links, though in
	 *   practice we currently only check if it is or isn't 'iconsfirst'
	 * * 'link-prefix' to set the prefix for all link and block ids; most skins use 'f' or 'footer',
	 *   as in id='f-whatever' vs id='footer-whatever'
	 * * 'icon-style' to pass to getFooterIcons: "icononly", "nocopyright"
	 * * 'link-style' to pass to getFooterLinks: "flat" to disable categorisation of links in a
	 *   nested array
	 *
	 * @return string html
	 */
	protected function getFooterBlock( $setOptions = [] ) {
		// Set options and fill in defaults
		$options = $setOptions + [
			'id' => 'footer',
			'class' => 'mw-footer',
			'order' => 'iconsfirst',
			'link-prefix' => 'footer',
			'icon-style' => 'icononly',
			'link-style' => null
		];

		// phpcs:ignore Generic.Files.LineLength.TooLong
		'@phan-var array{id:string,class:string,order:string,link-prefix:string,icon-style:string,link-style:?string} $options';

		$validFooterIcons = $this->getFooterIcons( $options['icon-style'] );
		$validFooterLinks = $this->getFooterLinks( $options['link-style'] );

		$html = '';

		$html .= Html::openElement( 'div', [
			'id' => $options['id'],
			'class' => $options['class'],
			'role' => 'contentinfo',
			'lang' => $this->get( 'userlang' ),
			'dir' => $this->get( 'dir' )
		] );

		$iconsHTML = '';
		if ( count( $validFooterIcons ) > 0 ) {
			$iconsHTML .= Html::openElement( 'ul', [ 'id' => "{$options['link-prefix']}-icons" ] );
			foreach ( $validFooterIcons as $blockName => $footerIcons ) {
				$iconsHTML .= Html::openElement( 'li', [
					'id' => Sanitizer::escapeIdForAttribute(
						"{$options['link-prefix']}-{$blockName}ico"
					),
					'class' => 'footer-icons'
				] );
				foreach ( $footerIcons as $icon ) {
					$iconsHTML .= $this->getSkin()->makeFooterIcon( $icon );
				}
				$iconsHTML .= Html::closeElement( 'li' );
			}
			$iconsHTML .= Html::closeElement( 'ul' );
		}

		$linksHTML = '';
		if ( count( $validFooterLinks ) > 0 ) {
			if ( $options['link-style'] === 'flat' ) {
				$linksHTML .= Html::openElement( 'ul', [
					'id' => "{$options['link-prefix']}-list",
					'class' => 'footer-places'
				] );
				foreach ( $validFooterLinks as $link ) {
					$linksHTML .= Html::rawElement(
						'li',
						[ 'id' => Sanitizer::escapeIdForAttribute( $link ) ],
						$this->get( $link )
					);
				}
				$linksHTML .= Html::closeElement( 'ul' );
			} else {
				$linksHTML .= Html::openElement( 'div', [ 'id' => "{$options['link-prefix']}-list" ] );
				foreach ( $validFooterLinks as $category => $links ) {
					$linksHTML .= Html::openElement( 'ul',
						[ 'id' => Sanitizer::escapeIdForAttribute(
							"{$options['link-prefix']}-{$category}"
						) ]
					);
					foreach ( $links as $link ) {
						$linksHTML .= Html::rawElement(
							'li',
							[ 'id' => Sanitizer::escapeIdForAttribute(
								"{$options['link-prefix']}-{$category}-{$link}"
							) ],
							$this->get( $link )
						);
					}
					$linksHTML .= Html::closeElement( 'ul' );
				}
				$linksHTML .= Html::closeElement( 'div' );
			}
		}

		if ( $options['order'] === 'iconsfirst' ) {
			$html .= $iconsHTML . $linksHTML;
		} else {
			$html .= $linksHTML . $iconsHTML;
		}

		$html .= $this->getClear() . Html::closeElement( 'div' );

		return $html;
	}
}
