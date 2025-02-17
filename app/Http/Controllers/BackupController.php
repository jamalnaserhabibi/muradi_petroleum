<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupController extends Controller
{
    // Show Backup Form
    public function backupform()
    {
        return view('backup.backup');
    }

    // Create Backup
        public function backup()
        {
            // Run Laravel backup command
          
        }

       // Restore Backup (Fixed)
       public function restore(Request $request)
       {
              // Get the backup file
            dd('in restore');
           return redirect()->back()->with('error', 'Failed to restore backup.');
       }
}

