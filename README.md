# Directory Manager
 
## Overview
 
The `DirectoryManager` is a PHP class that provides functionality to manage hierarchical directories through command line operations. It allows users to create, move, delete directories, and list the directory structure.
 
### Prerequisites
 
- PHP installed on your machine (PHP 8.2+ or higher recommended).
 
### Installation
 
1. Clone the repository:
 
   ```bash
   git clone https://github.com/yourusername/directory-manager.git
 
2. Change directory into the folder where the project has been cloned and ensure that you are in the  directory-manager directory
 
3. Run `php directory.php` command to initialize the command line
 
 
## Usage
 
Available commands and usage:
 
1. CREATE:
    - Creates a new directory.
    Usage: CREATE path/to/directory
 
2. MOVE:
    -  Moves a directory from the source path to the destination path.
    Usage: MOVE sourcePath destinationPath
 
3. DELETE:
    -  Deletes a directory.
    Usage: DELETE path/to/directory
 
4. LIST:
    -  Lists directories in a hierarchical structure.
    Usage: LIST
 
4. EXIT:
    -  Exits the command line.
    Usage: EXIT
 
## NOTES
- The script will continue to process commands until 'EXIT' is entered.
- For MOVE command, ensure both source and destination paths are valid.
- Invalid commands will be reported as "Unknown command."