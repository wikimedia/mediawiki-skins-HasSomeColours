<?php
/**
 * ResourceLoader module to set some LESS variables for the skin
 */
class HasSomeColoursVariablesModule extends ResourceLoaderSkinModule {
	/**
	 * Add our LESS variables
	 *
	 * @param ResourceLoaderContext $context
	 * @return array LESS variables
	 */
	protected function getLessVars( ResourceLoaderContext $context ) {
		$vars = parent::getLessVars( $context );
		$config = $this->getConfig();

		// Colours! This is HasSomeColours, after all! It needs colours!
		$primaryColour = $config->get( 'HasSomeColoursColourOne' );
		$secondaryColour = $config->get( 'HasSomeColoursColourTwo' );

		$headerTextColour = '#fff';
		$headerLinkColour = '#def';

		// TODO: actually validate colours
		// TODO: if primaryColour is light, swap headerTextColour

		// TODO: figure out a good header-link colour for some differentiation,
		// even if we only actually use it for footer stuff...

		$vars = array_merge(
			$vars,
			[
				'primary' => $primaryColour,
				'secondary' => $secondaryColour,
				'header-text' => $headerTextColour,
				'header-link' => $headerLinkColour
			]
		);

		return $vars;
	}

	/**
	 * Register the config var with the caching stuff so it properly updates the cache
	 *
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getDefinitionSummary( ResourceLoaderContext $context ) {
		$summary = parent::getDefinitionSummary( $context );
		$summary[] = [
			'HasSomeColoursColourOne' => $this->getConfig()->get( 'HasSomeColoursColourOne' ),
			'HasSomeColoursColourTwo' => $this->getConfig()->get( 'HasSomeColoursColourTwo' )
		];
		return $summary;
	}
}
