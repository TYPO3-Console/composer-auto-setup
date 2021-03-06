<?php
declare(strict_types=1);
namespace Typo3Console\ComposerAutoSetup\Composer;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Helmut Hummel <info@helhum.io>
 *  All rights reserved
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the text file GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Composer\Script\Event;
use Typo3Console\ComposerAutoSetup\Composer\InstallerScript\ConsoleCommand;
use Typo3Console\ComposerAutoSetup\Composer\InstallerScript\SetupTypo3;
use TYPO3\CMS\Composer\Plugin\Core\InstallerScriptsRegistration;
use TYPO3\CMS\Composer\Plugin\Core\ScriptDispatcher;

class InstallerScripts implements InstallerScriptsRegistration
{
    public static function register(Event $event, ScriptDispatcher $scriptDispatcher)
    {
        $scriptDispatcher->addInstallerScript(new SetupTypo3(), 69);

        $isSetupRunClosure = function () {
            return !getenv('TYPO3_IS_SET_UP');
        };
        $scriptDispatcher->addInstallerScript(
            new ConsoleCommand(
                'install:generatepackagestates',
                [],
                '',
                $isSetupRunClosure
            ),
            65
        );
        $scriptDispatcher->addInstallerScript(
            new ConsoleCommand(
                'install:fixfolderstructure',
                [],
                '',
                $isSetupRunClosure
            ),
            65
        );
        if ($event->isDevMode()) {
            $scriptDispatcher->addInstallerScript(
                new ConsoleCommand(
                    'install:extensionsetupifpossible',
                    [],
                    'Setting up TYPO3 environment and extensions.',
                    $isSetupRunClosure
                ),
                61
            );
        }
    }
}
