<?php

$file = $argv[3] ? $argv[3] : "./file0.txt";
$handler = fopen($file, 'r');

//formate le fichier sous forme d'array
$buffer = NULL;
$tab = array();
if ($handler) {
    while (($buffer = fgets($handler)) !== false) {
        $tab[] = explode(' ', $buffer);
    }
    fclose($handler);
}
else
	exit("Unreadable");

$C = intval($tab[0][0]);
$F = intval($tab[0][1]);
$origin = NULL;
$destination = NULL;

class Node
{
	public $self;
	public $links ;

	function __construct($self = NULL, $links = array())
	{
		$this->self = $self;
		$this->links = $links;
	}
}

// creation des chemins
$nodes = array();
for ($i = 1; $i <= $C; $i++)
{
	$links = array();
	$self = NULL;
	
	for($j = 1; $j <= $F; $j++)
	{
		if ($tab[$j][0] == $i)
		{
			$links[$tab[$j][1]] = intval($tab[$j][2]);
			$self = $tab[$j][0];
		}
		else if($tab[$j][1] == $i)
		{
			$links[$tab[$j][0]] = intval($tab[$j][2]);
			$self = $tab[$j][1];
		}
	}
	
	$nodes[$i-1] = new Node($self, $links);
	
	if($i == intval($argv[1]))
		$origin = $nodes[$i-1];
	if($i == intval($argv[2]))
		$destination = $nodes[$i-1];
}

// retive un noeud de la liste
function removeLinks($nodes, $ref)
{
	foreach($nodes as $node)
	{
		foreach($node->links as $target => $weight)
		{
			if($target == $ref)
			{
				unset($node->links[$ref]);
			}   
		}
	}
	unset($nodes[$ref-1]);
	
	return $nodes;
}

// recherche le chemin le moins cher
function find($nodes, $current, $destination, $sum = NULL, &$best = NULL)
{
	//var_dump($nodes);
	if($current->self == $destination->self)
	{
		if($best == NULL || $best > $sum)
			$best = $sum;
		return $sum;
	}
	
	if($current->self != $destination->self)
	{
		$nextNodes = removeLinks($nodes, $current->self);
		foreach($current->links as $target => $weight)
		{		
			$next = $nodes[$target-1];
			$find = find($nextNodes, $next, $destination, $sum + $weight, $best);
		}
		return $best;
	}
}

//var_dump($nodes);
//print("removelink 1\n");
//var_dump(removeLinks($nodes, 1));
//print("origin = ".$origin->self."\n"."destination = ".$destination->self."\n");
print find($nodes, $origin, $destination);

























