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
        Artisan::call('backup:run');

        return redirect()->back()->with('success', 'Backup created successfully.');
    }

       // Restore Backup (Fixed)
       public function restore(Request $request)
       {
           $request->validate([
               'backup' => 'required|file|mimes:zip',
           ]);
   
           $file = $request->file('backup');
           $backupPath = storage_path('app/backups/');
   
           // Move uploaded backup to the backup directory
           $file->move($backupPath, $file->getClientOriginalName());
   
           // Extract the backup file
           $zip = new ZipArchive;
           $zipFilePath = $backupPath . $file->getClientOriginalName();
   
           if ($zip->open($zipFilePath) === TRUE) {
               $zip->extractTo(base_path()); // Extract to project root
               $zip->close();
               return redirect()->back()->with('success', 'Backup restored successfully.');
           }
   
           return redirect()->back()->with('error', 'Failed to restore backup.');
       }
}

