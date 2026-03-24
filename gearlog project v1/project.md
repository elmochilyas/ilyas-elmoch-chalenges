Technical Requirements
1. Database Layer (MySQL)
You must implement a relational structure with two tables:
categories table: Stores hardware types (e.g., Laptops, Monitors, Servers).
assets table: Stores device details including serial_number (Unique), device_name, price (Decimal), status (Enum), and a category_id linked to the categories table.
2. PHP Backend Logic
Secure Connection: Use a centralized db.php file with PDO and try/catch error handling.
Prepared Statements: All SQL queries must use placeholders to prevent SQL injection.
Data Sanitization: Use htmlspecialchars() on all outputs to prevent XSS attacks.
3. User Interface (HTML/CSS)
While design is secondary, you should mobilize the following:
Semantic HTML: Proper use of <table> for the dashboard and <form> for data entry.
CSS Layout: Use Flexbox or Grid for alignment.
Conditional Styling: Use colors to indicate status (e.g., Red for 'Under Repair', Green for 'Deployed').

 ⇒ Challenges & Advanced Requests
Relational Joins: The main dashboard must use an INNER JOIN to display the category name instead of just the ID.
Financial Aggregation: Use SQL SUM(price) to display the total value of the current inventory at the top of the page.
Dynamic Search: Implement a search bar using LIKE %...% to filter assets by name or serial number.
