<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory API</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --background-color: #f4f4f4;
            --text-color: #333;
            --code-background: #2d2d2d;
            --code-color: #f8f8f2;

        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
        }

        header {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 1rem;
        }

        nav {
            background-color: var(--secondary-color);
            color: white;
            padding: 0.5rem;
            list-style-type: none;

        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;

        }

        nav ul li {
            margin: 0 1rem;
        }

        nav ul li a {
            color: white;
            text-decoration: none;

        }

        ul {
            list-style: none;

        }

        main {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .endpoint {
            background-color: white;
            border-radius: 5px;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .endpoint h2 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .method {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-weight: bold;
            margin-right: 0.5rem;
        }

        .get { background-color: #61affe; color: white; }
        .post { background-color: #49cc90; color: white; }
        .patch { background-color: #2ca39b  ; color: white; }
        .put { background-color: #fca130; color: white; }
        .delete { background-color: #f93e3e; color: white; }

        pre {
            background-color: var(--code-background);
            color: var(--code-color);
            padding: 1rem;
            border-radius: 5px;
            overflow-x: auto;
            margin-top: 1rem;
        }

        code {
            font-family: 'Courier New', Courier, monospace;
        }

        /* Syntax highlighting */
        .string { color: #a6e22e; }
        .number { color: #ae81ff; }
        .boolean { color: #66d9ef; }
        .null { color: #f8f8f2; }
        .key { color: #f92672; }

        @media (max-width: 600px) {
            nav ul {
                flex-direction: column;
                align-items: center;
            }

            nav ul li {
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Floware Inventory API</h1>
    </header>
    <nav>
        <ul>
            <li><a href="#authentication">Authentication</a></li>
            <li><a href="#users">Users</a></li>
            <li><a href="#products">Products</a></li>
        </ul>
    </nav>
    <main>
        <h2>Introduction</h2>
        <p>Welcome to our API documentation. This API will allow you to access our inventory management system, 
                feel free to contact us if you need assistance!</p>
        <br>
        <section id="authentication">

            <h2>Authentication</h2>
            <p>To use this API, you must login using the credentials provided by the admin. 
                You will receive a Jason Web Token that you must include in the header of each request.</p>
             </p>
            <pre><code>token: Bearer {YOUR_API_KEY} </code></pre><br>
            <p></p>
        </section>

        <br>
        <section id="users">

            <h2>Endpoints</h2>
            <div class="endpoint">
                <h3><span class="method post">POST</span>floware.studio/api/login</h3>
                <p>Allows the user to log on & obtain a authentication token.</p>
                <h4>JSON Body:</h4>
                <ul>
                    <li><code>username</code> (required)</li>
                    <li><code>password</code> (required)</li>

                </ul>
                <pre><code>
{
    <span class="key">"username"</span>: <span class="string">"JohnDoe"</span>,
    <span class="key">"password"</span>: <span class="string">"password"</span>

}</code></pre><br>
                <h4>Response:</h4>
<pre><code>
{

    <span class="key">"message"</span>: <span class="string">"User authenticated"</span>,
    <span class="key">"token"</span>: <span class="string">"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9eyJ0eXAiOi
        JKV1QiLCJhbGciOiJIUzI1NiJ9eyJ0eXAiOiJKV1QiLCJhbGciOiJNiJ9"</span>,

}</code></pre>
                    </div>
                    <div class="endpoint">
                        <h3><span class="method post">POST</span> floware.studio/api/createuser</h3>
                        <p>Create a new user</p>
                        <h4>JSON Body:</h4>
                <ul>
                    <li><code>username</code> (required)</li>
                    <li><code>password</code> (required)</li>
                    <li><code>email</code> (required)</li>
                    <li><code>role (staff, manager, admin) </code> (required)</li>
                </ul>
                <pre><code>
{
        <span class="key">"username"</span>: <span class="string">"TestUser"</span>,
        <span class="key">"password"</span>: <span class="string">"TestPassword"</span>
        <span class="key">"email"</span>: <span class="string">"test@email.com"</span>
        <span class="key">"role"</span>: <span class="string">"staff"</span>

}</code></pre><br>
                        <h4>Response:</h4>
                        <pre><code>{
        <span class="key">"message"</span>: <span class="string">"User created"</span>
        <span class="key">"id"</span>: <span class="number">42</span>,

}</code></pre>
</section>
<section id="products">
                    </div>
                    <div class="endpoint">
                        <h3><span class="method get">GET</span> floware.studio/api/getproduct</h3>
                        <p>Returns a specific product or the whole list</p>
                        <h4>Request Body:</h4>
                <ul>
                    <li><code>id</code> (optional)</li>
                    <li><code>floware.studio/api/getproduct?id=1</code></li>

                </ul>
                        <h4>Response:</h4>
                        <pre><code>{
        <span class="key">"id"</span>: <span class="number">1</span>,
        <span class="key">"name"</span>: <span class="string">"Product 1"</span>,
        <span class="key">"description"</span>: <span class="string">"this is a description"</span>
        <span class="key">"barcode"</span>: <span class="string">"A182373A"</span>
        <span class="key">"sku"</span>: <span class="string">"GA-KAJ81"</span>
        <span class="key">"price"</span>: <span class="number">"2499"</span>
        <span class="key">"cost"</span>: <span class="number">"1999"</span>
        <span class="key">"quantity"</span>: <span class="number">"5"</span>
        <span class="key">"created_at"</span>: <span class="string">"2024-08-24 22:54:25"</span>
        <span class="key">"updated_at"</span>: <span class="string">"2024-08-25 00:00:58"</span>

}</code></pre>
                    </div>
                    <div class="endpoint">
                        <h3><span class="method post">POST</span> floware.studio/api/addproduct</h3>
                        <p>Add a product to the inventory</p>
                        <h4>JSON Body:</h4>
                <ul>
                    <li><code>name</code> (required)</li>
                    <li><code>description</code> (required)</li>
                    <li><code>sku</code> (optional)</li>
                    <li><code>barcode</code> (optional)</li>
                    <li><code>price</code> (required)</li>
                    <li><code>cost</code> (optional)</li>
                    <li><code>quantity</code> (required)</li>


                </ul>
                <pre><code>
{
        <span class="key">"name"</span>: <span class="string">"Product 2"</span>,
        <span class="key">"description"</span>: <span class="string">"this is a description"</span>
        <span class="key">"sku"</span>: <span class="string">"XXXXXXX"</span>
        <span class="key">"barcode"</span>: <span class="string">"A182373A"</span>
        <span class="key">"price"</span>: <span class="number">"2499"</span>
        <span class="key">"cost"</span>: <span class="number">"1999"</span>
        <span class="key">"quantity"</span>: <span class="number">"5"</span>

}</code></pre><br>
                        <h4>Response:</h4>
                        <pre><code>{
        <span class="key">"message"</span>: <span class="string">"Product created"</span>
        <span class="key">"id"</span>: <span class="number">10</span>,

}</code></pre>
                    </div>
                    <div class="endpoint">
                        <h3><span class="method patch">PATCH</span> floware.studio/api/updateproduct</h3>
                        <p>Update product info</p>
                        <h4>Request Body:</h4>
                <ul>
                    <li><code>id</code> (required)</li>
                    <li><code>floware.studio/api/updateproduct?id=1</code></li>

                </ul>
                        <h4>Response:</h4>
                        <pre><code>{
        <span class="key">"id"</span>: <span class="number">1</span>,
        <span class="key">"name"</span>: <span class="string">"test product"</span>,
        <span class="key">"sku"</span>: <span class="string">"test description"</span>
        <span class="key">"barcode"</span>: <span class="string">"A182373A"</span>
        <span class="key">"price"</span>: <span class="number">"2499"</span>
        <span class="key">"cost"</span>: <span class="number">"1999"</span>
        <span class="key">"quantity"</span>: <span class="number">"5"</span>
        <span class="key">"created_at"</span>: <span class="string">"2024-08-24 22:54:25"</span>
        <span class="key">"updated_at"</span>: <span class="string">"2024-08-25 00:00:58"</span>

}</code></pre>
                    </div>

                    <div class="endpoint">
                        <h3><span class="method delete">DELETE</span> floware.studio/api/deleteproduct</h3>
                        <p>Returns a specific product or the whole list</p>
                        <h4>Request Body:</h4>
                <ul>
                    <li><code>id</code> (required)</li>
                    <li><code>floware.studio/api/deleteproduct?id=1</code></li>

                </ul>
                        <h4>Response:</h4>
                        <pre><code>{
        <span class="key">"message"</span>: <span class="string">"Product removed"</span>
        <span class="key">"id"</span>: <span class="number">1</span>,

}</code></pre>
                    </div>
                </section>
            </main>
        </body>
        </html>
