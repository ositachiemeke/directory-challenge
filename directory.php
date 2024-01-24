#!/usr/bin/env php
<?php

/**
 * Class DirectoryManager
 * Manages hierarchical directories through command line operations.
 */
class DirectoryManager
{
    /**
     * @var array Holds the directory structure.
     */
    private $directories = [];

    /**
     * DirectoryManager constructor.
     * Initializes the DirectoryManager and starts processing commands.
     */
    public function __construct()
    {
        $this->processCommands();
    }

    /**
     * Continuously processes user commands until 'EXIT' is entered.
     */
    private function processCommands()
    {
        while (true) {
            $command = readline("Enter a command (or type 'EXIT' to exit): ");
            $this->executeCommand($command);
        }
    }

    /**
     * Retrieves a reference to the directory specified by the given path.
     *
     * @param string $path The path of the directory.
     *
     * @return array Reference to the directory.
     */
    private function &getDirectoryByPath($path)
    {
        $pathParts = explode('/', $path);
        $currentDir = &$this->directories;

        foreach ($pathParts as $part) {
            if (!isset($currentDir[$part])) {
                $currentDir[$part] = [];
            }
            $currentDir = &$currentDir[$part];
        }

        return $currentDir;
    }

    /**
     * Retrieves or creates a reference to the directory specified by the given path.
     *
     * @param string $path The path of the directory.
     *
     * @return array Reference to the directory.
     */
    private function &getOrCreateDirectoryByPath($path)
    {
        $pathParts = explode('/', $path);
        $currentDir = &$this->directories;

        foreach ($pathParts as $part) {
            if (!isset($currentDir[$part])) {
                $currentDir[$part] = [];
            }
            $currentDir = &$currentDir[$part];
        }

        return $currentDir;
    }

    /**
     * Executes the provided command.
     *
     * @param string $command The command to execute.
     */
    private function executeCommand($command)
    {
        $parts = explode(' ', $command);
        $operation = strtoupper($parts[0]);

        echo "Executing command: $command\n";

        switch ($operation) {
            case 'CREATE':
                $this->createDirectory(implode(' ', array_slice($parts, 1)));
                break;

            case 'MOVE':
                if (isset($parts[1]) && isset($parts[2])) {
                    $this->moveDirectory($parts[1], $parts[2]);
                } else {
                    echo "Invalid MOVE command. Format: MOVE sourcePath destinationPath\n";
                }
                break;

            case 'DELETE':
                $this->deleteDirectory($parts[1]);
                break;

            case 'LIST':
                $this->listDirectories();
                break;

            case 'EXIT':
                echo "Exiting the script.\n";
                exit;
                break;

            default:
                echo "Unknown command: $command\n";
        }
    }

    /**
     * Creates a new directory specified by the given path.
     *
     * @param string $path The path of the new directory.
     */
    private function createDirectory($path)
    {
        try {
                if (!$this->isValidPath($path)) {
                    echo "Invalid path format. Please enter a valid path.\n";
                    return;
                }

                if ($this->directoryExists($path)) {
                    echo "Directory already exists: $path\n";
                    return;
                }

                $parts = explode('/', $path);
                $currentDir = &$this->directories;

                foreach ($parts as $part) {
                    if (!isset($currentDir[$part])) {
                        $currentDir[$part] = [];
                    }

                    $currentDir = &$currentDir[$part];
                }

                echo "Created directory: $path\n";
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Checks if the given path is valid.
     *
     * @param string $path The path to check.
     *
     * @return bool True if the path is valid, false otherwise.
     */
    private function isValidPath($path)
    {
        return is_string($path) && trim($path) !== '';
    }

    /**
     * Checks if the directory specified by the given path exists.
     *
     * @param string $path The path of the directory.
     *
     * @return bool True if the directory exists, false otherwise.
     */
    private function directoryExists($path)
    {
        $currentDir = &$this->directories;

        $parts = explode('/', $path);
        foreach ($parts as $part) {
            if (!isset($currentDir[$part])) {
                return false;
            }
            $currentDir = &$currentDir[$part];
        }

        return true;
    }

    /**
     * Moves a directory from the source path to the destination path.
     *
     * @param string $sourcePath      The source path of the directory to move.
     * @param string $destinationPath The destination path.
     */
    private function moveDirectory($sourcePath, $destinationPath)
    {
        try {
                if (!$this->isValidPath($sourcePath) || !$this->isValidPath($destinationPath)) {
                    echo "Invalid path format. Please enter valid paths.\n";
                    return;
                }

                $sourceDir = $this->getDirectoryByPath($sourcePath);

                if ($sourceDir === null) {
                    echo "Cannot move $sourcePath - $sourcePath does not exist\n";
                    return;
                }

                $destinationDir = &$this->getOrCreateDirectoryByPath($destinationPath);

                $itemName = basename($sourcePath);

                if (!is_array($destinationDir)) {
                    $destinationDir = [];
                }

                $destinationDir[$itemName] = array_merge($sourceDir);

                $this->removeDirectory($this->directories, $sourcePath);

                $this->updateDirectories($destinationDir, $destinationPath);

                echo "Moved $itemName from $sourcePath to $destinationPath\n";
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Updates the root directory after moving a directory to a new location.
     *
     * @param array $directory The new directory structure to merge.
     * @param string $path      The path of the directory.
     */
    private function updateDirectories(&$directory, $path)
    {
        try {
                $pathParts = explode('/', $path);
                $currentDir = &$this->directories;

                foreach ($pathParts as $part) {
                    if (!isset($currentDir[$part])) {
                        $currentDir[$part] = [];
                    }
                    $currentDir = &$currentDir[$part];
                }

                $currentDir = array_merge($currentDir, $directory);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Deletes a directory specified by the given path.
     *
     * @param string $path The path of the directory to delete.
     */
    private function deleteDirectory($path)
    {
        try {
            
                if (!$this->isValidPath($path)) {
                    echo "Invalid path format. Please enter a valid path.\n";
                    return;
                }

                $directoryToDelete = &$this->getDirectoryByPath($path);

                if ($directoryToDelete === null) {
                    echo "Cannot delete $path - $path does not exist\n";
                    return;
                }

                $this->removeDirectory($this->directories, $path);

                echo "Deleted directory: $path\n";
        } catch (\Throwable $th) {
                throw $th;
        }
    }

    /**
     * Lists directories in a hierarchical structure.
     *
     * @param array|null $directories The directory structure to list.
     * @param string     $indent      The indentation string.
     */
    private function listDirectories($directories = null, $indent = '')
    {
        try {
                if ($directories === null) {
                    $directories = $this->directories;
                }

                foreach ($directories as $directory => $subdirectories) {
                    echo $indent . $directory . "\n";
                    if (!empty($subdirectories)) {
                        $this->listDirectories($subdirectories, $indent . '  ');
                    }
                }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Removes a directory specified by the given path.
     *
     * @param array  $directory The root directory.
     * @param string $path      The path of the directory to remove.
     */
    private function removeDirectory(&$directory, $path)
    {
        $pathParts = explode('/', $path);
        $lastPart = array_pop($pathParts);
        $currentDir = &$directory;

        foreach ($pathParts as $part) {
            if (!isset($currentDir[$part])) {
                return;
            }
            $currentDir = &$currentDir[$part];
        }

        unset($currentDir[$lastPart]);
    }
}

// Instantiate DirectoryManager
$manager = new DirectoryManager();
