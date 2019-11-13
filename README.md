# Swiftly
## About

Swiftly is a simple, small footprint, MVC-esque framework designed for quickly
prototyping small sites or systems. By no means is it a fully featured
framework, and anyone looking to build anything of any serious complexity or
scope should probably stop reading here and find a more appropriate tool.

Swiftly originally started life as a learning project in the summer of 2019, but
has since quickly grown into the mini framework that exists today. Swiftly is
not suitable for production environments. It lacks any real security or advanced
features and isn't even guaranteed to work in most cases. However, if you just
need a scaffold against which to quickly throw together a prototype, Swiftly
might just be the no-nonsense framework you're after!

## Structure

The structure of code in Swiftly is very similar to most of the MVC frameworks
currently available. Your code will mostly be separated out into controllers,
models and views.

### Controllers

Controllers are the main drivers of the framework. Here, the incoming request
is processed, business logic performed and user input handled. For typical web
applications, the job of almost every controller will be to organise
communication with the database through Models and to return a rendered View
file.

All Controllers live in the *App/Controller* directory.

### Models

Models, as in most frameworks, handle communicating with the database and making
sure the responses are represented in useful formats.

TODO:

All Models live in the *App/Model* directory.

### Views

TODO:

All Views live in the *App/View* directory.

## Usage

// TODO: How do you use it

## Reference

// TODO: Document important functions
