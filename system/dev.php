<?php
use Inphinit\Experimental\Debug;

// Inject CSS for debug if necessary
Debug::view('before', 'debug.style');

// Display errors
Debug::view('error', 'debug.error');

// Display declared classes (uncomment next line for check used classes)
#Debug::view('classes', 'debug.classes');

//Display memory usage (uncomment next line for check memory peak usage and time)
#Debug::view('performance', 'debug.performance');
