<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floware API</title>
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
        }

        nav ul {
            list-style-type: none;
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
        <h1>Floware API</h1>
    </header>
    <nav>
        <ul>
            <li><a href="#introduction">Introduction</a></li>
            <li><a href="#authentication">Authentication</a></li>
            <li><a href="#endpoints">Endpoints</a></li>
        </ul>
    </nav>
    <main>
        <section id="introduction">

            <h2>Introduction</h2>
            <p>Welcome to our API documentation. This API will allow you to access our inventory management system, 
                do remember we have a mobile app!</p>

        </section>
        <br>
        <section id="authentication">

            <h2>Authentication</h2>
            <p>To use this API, you must login using the credentials provided by the admin. 
                You will receive a Jason Web Token that you must include in the header of each request.</p>
             </p>
            <pre><code>token: {YOUR_API_KEY} </code></pre><br>
            <p></p>

        </section>
        <br>
        <section id="endpoints">

            <h2>Endpoints</h2>
            <div class="endpoint">
                <h3><span class="method post">POST</span>/floware/api/login</h3>
                <p>Allows the user to log on & obtain a authentication token.</p>
                <h4>JSON Body:</h4>
                <ul>
                    <li><code>username</code> (required)</li>
                    <li><code>passoword</code> (required)</li>
                </ul>
                <pre><code>
{
    <span class="key">"username"</span>: <span class="string">"JohnDoe"</span>,
    <span class="key">"password"</span>: <span class="string">"password"</span>

}</code></pre>

                <h4>Response:</h4>
                <pre><code>
{

    <span class="key">"message"</span>: <span class="string">"User authenticated"</span>,
    <span class="key">"token"</span>: <span class="string">"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9eyJ0eXAiOi
        JKV1QiLCJhbGciOiJIUzI1NiJ9eyJ0eXAiOiJKV1QiLCJhbGciOiJNiJ9"</span>,

}</code></pre>
                    </div>
                    <div class="endpoint">
                        <h3><span class="method get">GET</span> /api/users</h3>
                        <p>Create a new user.</p>
                        <h4>Request Body:</h4>
                        <pre><code>{
        <span class="key">"name"</span>: <span class="string">"Jane Doe"</span>,
        <span class="key">"email"</span>: <span class="string">"jane@example.com"</span>,
        <span class="key">"password"</span>: <span class="string">"securepassword"</span>
        }</code></pre>
                        <h4>Response:</h4>
                        <pre><code>{
        <span class="key">"id"</span>: <span class="number">101</span>,
        <span class="key">"name"</span>: <span class="string">"Jane Doe"</span>,
        <span class="key">"email"</span>: <span class="string">"jane@example.com"</span>
        }</code></pre>
                    </div>
                </section>
            </main>
        </body>
        </html>
