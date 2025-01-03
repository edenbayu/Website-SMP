<?php

namespace App\Observers;

use App\Models\JamPelajaran;

class JamPelajaranObserver
{
    private function updateNomor()
    {
        $jampels = JamPelajaran::orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jam_mulai ASC")->get();
        $counter = 1;
        $previousDay = null;

        $jampels->each(function ($jampel) use (&$counter, &$previousDay) {
            // Jika hari berbeda dari hari sebelumnya, reset counter
            if ($previousDay !== $jampel->hari) {
                $counter = 1;
            }

            // Tentukan nomor berdasarkan kondisi
            if (is_null($jampel->event) || $jampel->event == "Upacara") {
                $jampel->nomor = $counter++;
            } else {
                $jampel->nomor = null;
            }
            $jampel->saveQuietly();

            // Set hari sebelumnya untuk perbandingan di iterasi berikutnya
            $previousDay = $jampel->hari;
        });
    }
    
    public function creating(JamPelajaran $jamPelajaran)
    {
        // Logika sebelum membuat data baru
    }

    public function created(JamPelajaran $jamPelajaran)
    {
        $this->updateNomor();
    }

    public function updating(JamPelajaran $jamPelajaran)
    {
        // Logika sebelum memperbarui data
    }

    public function updated(JamPelajaran $jamPelajaran)
    {
        $this->updateNomor();
    }

    public function deleting(JamPelajaran $jamPelajaran)
    {
        // Logika sebelum menghapus data
    }

    public function deleted(JamPelajaran $jamPelajaran)
    {
        $this->updateNomor();
    }

    public function restoring(JamPelajaran $jamPelajaran)
    {
        // Logika sebelum memulihkan data (soft delete)
    }

    public function restored(JamPelajaran $jamPelajaran)
    {
        $this->updateNomor();
    }

    public function saving(JamPelajaran $jamPelajaran)
    {
        // Logika sebelum menyimpan data (create/update)
    }

    public function saved(JamPelajaran $jamPelajaran)
    {
        $this->updateNomor();
    }
}
