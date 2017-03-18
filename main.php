<?php
$file = "./file.txt";
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
				print("removed link ".$ref." in node ".$node->self."\n");
			}
		}
	}
	print("removed node : ".$nodes[$ref-1]->self."\n");
	//var_dump($nodes[$ref-1]);
	
	unset($nodes[$ref-1]);
	
	return $nodes;
}

// recherche le chemin le moins cher
function find($nodes, $current, $destination, $sum = NULL)
{
	print("-----------------------------\n");
	//var_dump($nodes);
	if($current->self == $destination->self)
	{
		print ("final sum = ".$sum." !!!!!\n");
		return $sum;
	}
	
	if($current->self != $destination->self)
	{
		$nodes = removeLinks($nodes, $current->self);
		foreach($current->links as $target => $weight)
		{		
			$next = $nodes[$target-1];
			print("current = ".$current->self."\n");
			print("next = ".$next->self."\n");
			if($sum > ($find = find($nodes, $next, $destination, $sum + $weight)) || $sum == NULL)
				$sum = $find;
		}
		return $sum;
	}
}

//var_dump($nodes);
//print("removelink 1\n");
//var_dump(removeLinks($nodes, 1));
//print("origin = ".$origin->self."\n"."destination = ".$destination->self."\n");
print find($nodes, $origin, $destination);

























