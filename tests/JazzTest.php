<?php

class JazzTest extends \PHPUnit_Framework_TestCase
{
    function testShortTag()
    {
        $this->assertEquals(
            "<br />",
            Jazz::render(["#br"])->saveHTML()
        );
    }

    function testShortTagWithAttributes()
    {
        $this->assertTag(
            ["tag" => "img", "attributes" => ["src" => "/foo/bar.png"]],
            Jazz::render(["#img", ["src" => "/foo/bar.png"]])
        );
    }

    function testShortTagWithContent()
    {
        $this->assertTag(
            ["tag" => "p", "content" => "Foo"],
            Jazz::render(["#p", "Foo"])
        );
    }

    function testRendersEmptyBody()
    {
        $this->assertEquals(
            '<p></p>',
            Jazz::render(["#p", ""])->saveHTML()
        );
    }

    function testTagWithAttributesAndBody()
    {
        $this->assertTag(
            [
                "tag" => "h1",
                "attributes" => ["role" => "banner"],
                "content" => "Unicorns and Rainbows"
            ],
            Jazz::render(
                ["#h1", ["role" => "banner"], "Unicorns and Rainbows"]
            )->saveHTML()
        );
    }

    function testTagWithNestedTags()
    {
        $this->assertTag(
            [
                "tag" => "nav",
                "attributes" => ["class" => "main"],
                "child" => [
                    "tag" => "a",
                    "attributes" => ["href" => "/"],
                    "content" => "Front Page"
                ]
            ],
            Jazz::render(["#nav", ["class" => "main"], [
                ["#a", ["href" => "/"], "Front Page"]
            ]])
        );
    }

    function testEncodesTextContent()
    {
        return $this->markTestSkipped("Have to think about this feature...");

        $this->assertEquals(
            '<p>This is encoded: &lt;p&gt;</p>',
            Jazz::render(["#p", "This is encoded: <p>"])
        );
    }

    function testNoEscape()
    {
        return $this->markTestSkipped("Have to think about this feature...");

        $this->assertEquals(
            '<p>This is not encoded: <a name="bar">Foo</a></p>',
            Jazz::render(
                ["#p", [
                    ["!!", 'This is not encoded: <a name="bar">Foo</a>']
                ]]
            )
        );
    }

    function testJoinsNodeListWithoutRootElement()
    {
        $this->assertEquals(
            "<p>Foo</p><p>Bar</p>",
            Jazz::render([
                ["#p", "Foo"],
                ["#p", "Bar"]
            ])->saveHTML()
        );
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    function testThrowsExceptionWhenTooManyElementsInTag()
    {
        Jazz::render(["#p", "foo", "bar", "baz"]);
    }

    function testJoinsMultipleAttributeValues()
    {
        $expected = [
            "tag" => "p",
            "attributes" => [
                "class" => "foo bar baz"
            ]
        ];

        $actual = Jazz::render(["#p", ["class" => ["foo", "bar", "baz"], "Foo"]]);
        $this->assertTag($expected, $actual);
    }
}
