<?php

include 'simple_html_dom.php';
include '../helpers/Collection.php';
include '../model/Board.php';
include '../model/Note.php';
include '../model/Plan.php';

// $xml = file_get_html('./sample.xml');
$html = file_get_html('./sample.html');
$tags = new Collection();
foreach ($html->find('span.tag') as $element) {
    $tag = trim($element->plaintext);
    if (!$tags->contains($tag)) {
        $tags->add($tag);
    }
}

$plan = new Plan();
$plan->setName($html->find('span.plan-name')[0]->plaintext);
$plan->setId($html->find('span.plan-id')[0]->plaintext);
foreach ($html->find('div.board') as $boardId => $boardEl) {
    $board = new Board();
    if (!strpos($boardEl->class, 'new-board')) {
        $boardName = trim($boardEl->find('div.board-header')[0]->plaintext);
        $board->setName($boardName);
        $board->setId($boardId);
        foreach ($boardEl->find('div.note') as $noteId => $noteEl) {
            if (count($noteEl->find('.new-note')) != 0) {
                continue;
            }
            $note = new Note();
            $note->setId($noteId);
            foreach ($noteEl->find('span.tag') as $tagEl) {
                $tag = $tagEl->plaintext;
                if (isset($tag) && $tags->indexOf($tag)) {
                    $note->addTag($tag, $tags->indexOf($tag));
                }
            }
            $note->setContent(
                trim($noteEl->find('div.note-body')[0]->plaintext)
            );
            $board->addNote($note);
        }
        $plan->addBoard($board);
    }
}

$dom = new DOMDocument();

$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
// End initial block

// $domxml = new DOMDocument('1.0');
$domxml = new DOMDocument();
$domxml->preserveWhiteSpace = false;
$domxml->formatOutput = true;

$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>  {$plan->toXML()}";
$domxml->loadXML($xml);
$output = $domxml->saveXML();

// $domxml->loadHTML($plan->toHTML());
// $output = $domxml->saveHTML();

echo sprintf(
    "<code>
%s
</code>",
    nl2br(htmlentities($output))
);
?>
