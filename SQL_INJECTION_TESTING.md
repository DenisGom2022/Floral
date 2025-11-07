# SQL Injection Testing Guide

## Overview
The catalog now includes a search functionality that is intentionally vulnerable to SQL injection attacks for educational purposes.

## How to Test

### 1. Normal Search
- Search for: `rose` or `tulip`
- Should return normal flower results

### 2. Basic SQL Injection
- Search for: `' OR 1=1 --`
- This should return all products in the database
- The `--` comments out the rest of the query

### 3. Union-Based SQL Injection
- Search for: `' UNION SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17 --`
- This attempts to combine results from a crafted SELECT statement
- May reveal database structure information

### 4. Information Schema Access
- Search for: `' AND (SELECT COUNT(*) FROM information_schema.tables) > 0 --`
- Tests access to MySQL's information schema
- Can reveal database metadata

### 5. Error-Based Injection
- Search for: `' AND (SELECT * FROM (SELECT COUNT(*),CONCAT(version(),FLOOR(RAND(0)*2)) x FROM information_schema.tables GROUP BY x)a) --`
- Attempts to extract MySQL version information through error messages

### 6. Time-Based Blind Injection
- Search for: `' AND (SELECT SLEEP(5)) --`
- Should cause a 5-second delay if vulnerable
- Useful for blind SQL injection scenarios

## Vulnerable Code Location

The vulnerability is in `/includes/database.php` in the `searchProducts` method:

```php
public function searchProducts($searchTerm, $limit = 20) {
    // VULNERABLE QUERY - NO SANITIZATION
    $sql = "SELECT ... FROM products WHERE name LIKE '%" . $searchTerm . "%'";
    
    // DIRECT EXECUTION WITHOUT PREPARED STATEMENTS
    $result = $this->db->query($sql);
    // ...
}
```

## Educational Purpose

This implementation demonstrates:
1. **String concatenation vulnerability** - Direct insertion of user input
2. **No input validation** - No sanitization of search terms
3. **Error disclosure** - SQL errors are displayed to users
4. **No prepared statements** - Using `query()` instead of `prepare()`

## Security Implications

In a real application, this vulnerability could allow attackers to:
- Extract sensitive data from the database
- Modify or delete database records
- Bypass authentication mechanisms
- Access system information
- Potentially execute operating system commands

## How to Fix (For Learning)

To secure this code, you would:
1. Use prepared statements with parameter binding
2. Validate and sanitize user input
3. Implement proper error handling without disclosure
4. Use principle of least privilege for database connections
5. Implement input length limits and character filtering

## Safe Implementation Example

```php
public function searchProductsSafe($searchTerm) {
    $sql = "SELECT ... FROM products WHERE name LIKE ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['%' . $searchTerm . '%']);
    return $stmt->fetchAll();
}
```

## Testing Environment

Make sure to test this only in a development environment with test data. Never use these techniques on production systems or systems you don't own.