<?php
/**
 * SkinTemplate class for the HasSomeColours skin
 *
 * @ingroup Skins
 */
class SkinHasSomeColours extends SkinTemplate {
	public $skinname = 'hassomecolours';
	public $stylename = 'HasSomeColours';
	public $template = 'HasSomeColoursTemplate';

	/**
	 * Add CSS via ResourceLoader
	 *
	 * @param OutputPage $out
	 */
	public function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		$out->addMeta( 'viewport',
			'width=device-width, initial-scale=1.0, ' .
			'user-scalable=yes, minimum-scale=0.25, maximum-scale=5.0'
		);
		$out->addModuleStyles( [
			'mediawiki.skinning.content.externallinks',
			'skins.hassomecolours'
		] );
		$out->addModules( [ 'skins.hassomecolours.js' ] );
	}
}
