<?php namespace Fisharebest\Localization\Script;

/**
 * Class ScriptLeke - Representation of the Leke script.
 *
 * @author    Greg Roach <fisharebest@gmail.com>
 * @copyright (c) 2015 Greg Roach
 * @license   GPLv3+
 */
class ScriptLeke extends AbstractScript implements ScriptInterface {
	/** {@inheritdoc} */
	public function code() {
		return 'Leke';
	}

	/** {@inheritdoc} */
	public function number() {
		return '364';
	}
}
