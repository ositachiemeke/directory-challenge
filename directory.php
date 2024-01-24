<?php

class DirectoryManager
{
    private $directories = [];

    public function create($path)
    {
        $this->directories[$path] = [];
    }

    public function delete($path)
    {
        if (isset($this->directories[$path])) {
            unset($this->directories[$path]);
        } else {
            echo "Delete action failed - $path does not exist\n";
        }
    }

    public function move($origin, $destination)
    {
        if (isset($this->directories[$origin])) {
            $this->directories[$destination] = $this->directories[$origin];
            unset($this->directories[$origin]);
        } else {
            echo "Move action failed - $origin does not exist\n";
        }
    }

    public function list()
    {
        $this->display($this->directories);
    }

    private function display($directories, $indent = '')
    {
        foreach ($directories as $directory => $subdirectories) {
            echo $indent . $directory . "\n";
            if (!empty($subdirectories)) {
                $this->display($subdirectories, $indent . '  ');
            }
        }
    }
}



$manager = new DirectoryManager();

$manager->create('fruits');
$manager->create('vegetables');
$manager->create('grains');
$manager->create('fruits/apples');
$manager->create('fruits/apples/fuji');

echo "LIST\n";
$manager->list();

$manager->create('grains/squash');
$manager->move('grains/squash', 'vegetables');
$manager->create('foods');
$manager->move('grains', 'foods');
$manager->move('fruits', 'foods');
$manager->move('vegetables', 'foods');

echo "LIST\n";
$manager->list();

$manager->delete('fruits/apples');
$manager->delete('foods/fruits/apples');

echo "LIST\n";
$manager->list();

?>