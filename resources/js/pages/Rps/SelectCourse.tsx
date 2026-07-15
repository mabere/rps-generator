import { useState, useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { Sparkles, Trash2, Plus, ArrowLeft, Loader2 } from 'lucide-react';
import { index, store } from '@/routes/rps';

interface Cpl {
    id?: number;
    code: string;
    description: string;
    type: string;
}

interface Course {
    id: number;
    code: string;
    name: string;
    sks: number;
    semester: number;
    description: string;
    cpls?: Cpl[];
}

interface StudyProgram {
    id: number;
    name: string;
    code: string;
    cpls: Cpl[];
}

interface Props {
    studyPrograms: StudyProgram[];
    selectedCourse: Course | null;
    courses: Course[];
}

const CPL_TYPES = ['Sikap', 'Pengetahuan', 'Keterampilan Umum', 'Keterampilan Khusus'];

export default function SelectCourse({ studyPrograms, selectedCourse, courses }: Props) {
    const [currentCourse, setCurrentCourse] = useState<Course | null>(selectedCourse);
    const [loadingSteps, setLoadingSteps] = useState<string>('Memulai analisis kurikulum...');
    
    const { data, setData, post, processing } = useForm({
        course_id: currentCourse?.id?.toString() || '',
        cpls: (currentCourse?.cpls || []) as Cpl[],
    });

    useEffect(() => {
        if (currentCourse) {
            setData({
                course_id: currentCourse.id.toString(),
                cpls: currentCourse.cpls || [],
            });
        }
    }, [currentCourse]);

    // Loading stages sequence
    useEffect(() => {
        if (!processing) return;
        const steps = [
            'Menganalisis deskripsi kuliah...',
            'Menyusun CPMK sesuai standar kurikulum OBE...',
            'Membuat aktivitas pembelajaran mingguan (Minggu 1-14)...',
            'Merekomendasikan metode asesmen dan bobot penilaian...',
            'Menyusun bahan referensi pustaka...',
            'Finalisasi dokumen RPS...'
        ];
        
        let currentStep = 0;
        const interval = setInterval(() => {
            if (currentStep < steps.length - 1) {
                setLoadingSteps(steps[currentStep]);
                currentStep++;
            }
        }, 3000);

        return () => clearInterval(interval);
    }, [processing]);

    const handleCourseChange = (val: string) => {
        const found = courses.find(c => c.id.toString() === val);
        if (found) {
            // Find full course structure if possible
            const fullCourse = { ...found, cpls: found.cpls || [] };
            setCurrentCourse(fullCourse);
        }
    };

    const addManualCpl = () => {
        setData('cpls', [
            ...data.cpls,
            { code: `CPL-${data.cpls.length + 1}`, type: 'Pengetahuan', description: '' }
        ]);
    };

    const removeCpl = (index: number) => {
        const updated = [...data.cpls];
        updated.splice(index, 1);
        setData('cpls', updated);
    };

    const handleCplChange = (index: number, field: keyof Cpl, value: string) => {
        const updated = [...data.cpls];
        updated[index] = { ...updated[index], [field]: value };
        setData('cpls', updated);
    };

    const selectPredefinedCpl = (cpl: Cpl) => {
        if (data.cpls.some(c => c.code === cpl.code)) return;
        setData('cpls', [...data.cpls, { code: cpl.code, type: cpl.type, description: cpl.description }]);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(store.url());
    };

    return (
        <>
            <Head title="Generate RPS Baru" />
            
            {processing && (
                <div className="fixed inset-0 bg-neutral-900/80 backdrop-blur-md z-50 flex flex-col items-center justify-center text-white">
                    <div className="relative flex flex-col items-center p-8 bg-neutral-950/90 rounded-2xl border border-neutral-800 shadow-2xl max-w-md w-full text-center">
                        <Sparkles className="h-12 w-12 text-blue-500 animate-pulse mb-4" />
                        <Loader2 className="h-8 w-8 animate-spin text-indigo-500 mb-6" />
                        <h3 className="text-xl font-bold mb-2">Gemini AI sedang bekerja</h3>
                        <p className="text-sm text-neutral-400 min-h-[48px] px-4">
                            {loadingSteps}
                        </p>
                        <span className="text-[10px] text-neutral-500 mt-6">Proses ini memakan waktu sekitar 10-15 detik. Mohon tidak menutup halaman ini.</span>
                    </div>
                </div>
            )}

            <div className="flex-1 space-y-6 p-8 pt-6 max-w-4xl mx-auto">
                <div className="flex items-center space-x-2">
                    <Button variant="ghost" onClick={() => window.history.back()} className="text-muted-foreground hover:text-foreground">
                        <ArrowLeft className="h-4 w-4 mr-1.5" /> Kembali
                    </Button>
                </div>

                <form onSubmit={handleSubmit}>
                    <Card className="border border-sidebar-border/70 dark:border-sidebar-border shadow-xl bg-card/60 backdrop-blur-md overflow-hidden">
                        <CardHeader className="bg-gradient-to-r from-blue-500/10 to-indigo-500/10 border-b border-sidebar-border/50">
                            <CardTitle className="text-2xl font-bold flex items-center space-x-2">
                                <Sparkles className="h-6 w-6 text-blue-500" />
                                <span>Generate RPS OBE Baru</span>
                            </CardTitle>
                            <CardDescription>
                                Pilih mata kuliah dan tentukan CPL yang ingin dicapai. Gemini AI akan menyusun CPMK beserta rencana perkuliahan mingguan.
                            </CardDescription>
                        </CardHeader>
                        
                        <CardContent className="space-y-6 pt-6">
                            {/* Course Selection */}
                            <div className="space-y-2">
                                <Label htmlFor="course_id" className="text-sm font-semibold">Pilih Mata Kuliah</Label>
                                <Select value={data.course_id} onValueChange={handleCourseChange}>
                                    <SelectTrigger className="w-full">
                                        <SelectValue placeholder="--- Pilih Mata Kuliah ---" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {courses.map(c => (
                                            <SelectItem key={c.id} value={c.id.toString()}>
                                                {c.code} - {c.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            {currentCourse && (
                                <div className="p-4 rounded-xl bg-muted/30 border border-sidebar-border/50 grid grid-cols-2 gap-4 animate-in fade-in duration-300">
                                    <div>
                                        <span className="text-xs text-muted-foreground">SKS</span>
                                        <p className="font-semibold">{currentCourse.sks} SKS</p>
                                    </div>
                                    <div>
                                        <span className="text-xs text-muted-foreground">SemesterTarget</span>
                                        <p className="font-semibold">Semester {currentCourse.semester}</p>
                                    </div>
                                    <div className="col-span-2">
                                        <span className="text-xs text-muted-foreground">Deskripsi Singkat Mata Kuliah</span>
                                        <p className="text-sm text-neutral-700 dark:text-neutral-300 mt-1 leading-relaxed">
                                            {currentCourse.description || 'Tidak ada deskripsi mata kuliah.'}
                                        </p>
                                    </div>
                                </div>
                            )}

                            {/* CPL Selection / Creation */}
                            <div className="space-y-4">
                                <div className="flex items-center justify-between border-t border-sidebar-border/50 pt-4">
                                    <Label className="text-sm font-semibold">Daftar CPL Dibebankan pada MK</Label>
                                    <Button type="button" variant="outline" size="sm" onClick={addManualCpl}>
                                        <Plus className="h-4 w-4 mr-1.5" /> Tambah CPL Manual
                                    </Button>
                                </div>

                                {/* Predefined CPL Quick Add */}
                                {studyPrograms.length > 0 && (
                                    <div className="space-y-2">
                                        <span className="text-xs font-semibold text-muted-foreground">Pilih dari Daftar CPL Prodi:</span>
                                        <div className="flex flex-wrap gap-1.5 p-3 rounded-lg border border-dashed bg-muted/10 max-h-40 overflow-y-auto">
                                            {studyPrograms.flatMap(p => p.cpls).map(cpl => (
                                                <button
                                                    key={cpl.code}
                                                    type="button"
                                                    onClick={() => selectPredefinedCpl(cpl)}
                                                    className="inline-flex items-center"
                                                >
                                                    <Badge variant="outline" className="cursor-pointer hover:bg-blue-500/10 hover:border-blue-500/50 transition-colors py-1">
                                                        +{cpl.code} <span className="text-[10px] text-muted-foreground ml-1 font-normal line-clamp-1 max-w-[120px]">{cpl.description}</span>
                                                    </Badge>
                                                </button>
                                            ))}
                                        </div>
                                    </div>
                                )}

                                {/* Manual / Current List of CPLs */}
                                <div className="space-y-3">
                                    {data.cpls.map((cpl, index) => (
                                        <div key={index} className="flex flex-col md:flex-row gap-3 p-4 rounded-xl border border-sidebar-border/60 bg-card shadow-sm animate-in slide-in-from-bottom-2 duration-300">
                                            <div className="w-full md:w-32 space-y-1.5">
                                                <Label className="text-xs">Kode CPL</Label>
                                                <Input 
                                                    value={cpl.code}
                                                    onChange={(e) => handleCplChange(index, 'code', e.target.value)}
                                                    placeholder="Contoh: S1, P2" 
                                                    className="font-bold text-center"
                                                />
                                            </div>
                                            <div className="w-full md:w-44 space-y-1.5">
                                                <Label className="text-xs">Aspek/Jenis CPL</Label>
                                                <Select value={cpl.type} onValueChange={(val) => handleCplChange(index, 'type', val)}>
                                                    <SelectTrigger>
                                                        <SelectValue />
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        {CPL_TYPES.map(t => (
                                                            <SelectItem key={t} value={t}>{t}</SelectItem>
                                                        ))}
                                                    </SelectContent>
                                                </Select>
                                            </div>
                                            <div className="flex-1 space-y-1.5">
                                                <Label className="text-xs">Deskripsi Capaian Pembelajaran</Label>
                                                <Input 
                                                    value={cpl.description}
                                                    onChange={(e) => handleCplChange(index, 'description', e.target.value)}
                                                    placeholder="Deskripsi kemampuan yang diharapkan..." 
                                                />
                                            </div>
                                            <div className="flex items-end justify-end">
                                                <Button 
                                                    type="button" 
                                                    variant="ghost" 
                                                    size="icon" 
                                                    onClick={() => removeCpl(index)}
                                                    className="text-red-500 hover:text-red-600 hover:bg-red-500/10"
                                                >
                                                    <Trash2 className="h-5 w-5" />
                                                </Button>
                                            </div>
                                        </div>
                                    ))}

                                    {data.cpls.length === 0 && (
                                        <div className="text-center py-6 text-sm text-muted-foreground border border-dashed rounded-xl">
                                            Belum ada CPL yang dipilih atau ditambahkan.
                                        </div>
                                    )}
                                </div>
                            </div>
                        </CardContent>
                        
                        <CardFooter className="bg-muted/10 border-t border-sidebar-border/50 py-4 flex justify-between">
                            <Button type="button" variant="ghost" onClick={() => window.history.back()}>
                                Batal
                            </Button>
                            <Button 
                                type="submit" 
                                disabled={!currentCourse || data.cpls.length === 0 || processing}
                                className="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white shadow-md hover:scale-[1.01] active:scale-[0.99] transition-transform"
                            >
                                <Sparkles className="h-4 w-4 mr-2" /> Generate RPS
                            </Button>
                        </CardFooter>
                    </Card>
                </form>
            </div>
        </>
    );
}

SelectCourse.layout = {
    breadcrumbs: [
        {
            title: 'RPS OBE',
            href: '/rps',
        },
        {
            title: 'Buat Baru',
            href: '/rps/create',
        },
    ],
};
