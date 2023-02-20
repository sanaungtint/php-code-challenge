## Code Improvement

#### 1. Updated variables names to be more descriptive

#### 2. Remove unnecessary code
- This line 29 ` array_filter($rcs);` is not actually doing anything. It can be removed.

#### 3. Close file handle
- Close the opened file to free up system resources.

#### 4. Refactor code to improve maintainability
- Refactor code into smaller functions to improve readability and maintainability

#### 5. Remove closing PHP tag
- It is a recommended good practice to omit closing tag in a file contains on PHP code 