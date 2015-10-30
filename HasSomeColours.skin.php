<?php
/**
 * SkinTemplate class for the HasSomeColours skin
 *
 * @ingroup Skins
 */
class SkinHasSomeColours extends SkinTemplate {
	public $skinname = 'hassomecolours', $stylename = 'HasSomeColours',
		$template = 'HasSomeColoursTemplate', $useHeadElement = true;

	/**
	 * Add CSS via ResourceLoader
	 *
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		$out->addMeta( 'viewport', 'width=device-width, initial-scale=1.0' );

		$out->addModuleStyles( array(
			'mediawiki.skinning.content.externallinks',
			'skins.hassomecolours'
		) );
		$out->addModules( array( 'skins.hassomecolours.js' ) );
	}
}
