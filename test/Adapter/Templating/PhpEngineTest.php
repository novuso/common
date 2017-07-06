<?php

namespace Novuso\Test\Common\Application\Templating;

use Novuso\Common\Adapter\Templating\PhpEngine;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Adapter\Templating\PhpEngine
 */
class PhpEngineTest extends UnitTestCase
{
    /**
     * @var PhpEngine
     */
    protected $engine;

    protected function setUp()
    {
        $testDir = dirname(dirname(__DIR__));
        $paths = [sprintf('%s/Resources/templates', $testDir)];
        $this->engine = new PhpEngine($paths);
    }

    public function test_that_it_renders_template_as_expected()
    {
        $expected = <<<HTML
<!DOCTYPE html>
<html class="no-js" lang="en_US">
<head>
<meta charset="UTF-8">
<title>Project Index</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="/app_assets/css/project.css">
</head>
<body>
<div id="wrapper" class="wrapper">
<nav id="navigation" class="navigation" role="navigation">
    <ul>
        <li><a href="/home">Home</a></li>
    </ul>
</nav><!-- #navigation -->
<header id="header" class="header" role="banner">
    <h1>Project</h1>
</header><!-- #header -->
<div id="content" class="content">
    <h2>Index</h2>
    <p>Sample content -&gt; escaped</p>
</div><!-- #content -->
<footer id="footer" class="footer" role="contentinfo">
    <p>&copy; 2016 Novuso.</p>
</footer><!-- #footer -->
</div><!-- #wrapper -->
<script type="text/javascript" src="/app_assets/js/project.js"></script>
</body>
</html>
HTML;
        $data = ['content' => 'Sample content -> escaped'];
        $html = $this->engine->render('project:default:index.html.php', $data);
        $this->assertSame($expected, $html);
    }
}
