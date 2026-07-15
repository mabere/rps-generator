import { useState } from 'react';
import { Head, useForm, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { FileText, FileDown, Save, ArrowLeft, BookOpen, Layers, CheckCircle2 } from 'lucide-react';
import { index, update, exportMethod } from '@/routes/rps';

interface Cpl {
    id: number;
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
    study_program: {
        name: string;
    };
    cpls: Cpl[];
}

interface Cpmk {
    id: number;
    course_id: number;
    cpl_id: number;
    statement: string;
    indicators: string;
    cpl?: Cpl;
}

interface RpsWeek {
    id: number;
    rps_document_id: number;
    week_number: number;
    sub_cpmk: string;
    learning_methods: string;
    assessment_criteria: string;
    weight: number;
    resources: string;
}

interface RpsDocument {
    id: number;
    course_id: number;
    status: 'draft' | 'published';
    generated_at: string;
    course: Course;
    weeks: RpsWeek[];
}

interface Props {
    document: RpsDocument;
    cpmks: Cpmk[];
}

export default function RpsEdit({ document, cpmks: initialCpmks }: Props) {
    const [activeTab, setActiveTab] = useState<'info' | 'cpmk' | 'weeks'>('info');

    const { data, setData, put, processing, recentlySuccessful } = useForm({
        status: document.status,
        cpmks: initialCpmks.map(c => ({
            id: c.id,
            statement: c.statement,
            indicators: c.indicators,
        })),
        weeks: document.weeks.map(w => ({
            id: w.id,
            week_number: w.week_number,
            sub_cpmk: w.sub_cpmk,
            learning_methods: w.learning_methods || '',
            assessment_criteria: w.assessment_criteria || '',
            weight: w.weight,
            resources: w.resources || '',
        })),
    });

    const handleCpmkChange = (index: number, field: 'statement' | 'indicators', value: string) => {
        const updated = [...data.cpmks];
        updated[index] = { ...updated[index], [field]: value };
        setData('cpmks', updated);
    };

    const handleWeekChange = (index: number, field: keyof typeof data.weeks[0], value: any) => {
        const updated = [...data.weeks];
        updated[index] = { ...updated[index], [field]: value };
        setData('weeks', updated);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(update.url({ id: document.id }));
    };

    return (
        <>
            <Head title={`Edit RPS - ${document.course.name}`} />
            
            <div className="flex-1 space-y-6 p-8 pt-6 max-w-7xl mx-auto">
                {/* Top Actions */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div className="flex items-center space-x-2">
                        <Link href={index.url()}>
                            <Button variant="ghost">
                                <ArrowLeft className="h-4 w-4 mr-1.5" /> Daftar RPS
                            </Button>
                        </Link>
                        <h2 className="text-2xl font-bold tracking-tight text-neutral-800 dark:text-neutral-200">
                            Edit RPS: <span className="text-blue-500">{document.course.name}</span>
                        </h2>
                    </div>

                    <div className="flex items-center space-x-2">
                        <a href={exportMethod.url({ id: document.id, format: 'pdf' })} target="_blank" rel="noopener noreferrer">
                            <Button variant="outline" className="text-rose-600 hover:text-rose-700 dark:text-rose-400">
                                <FileText className="mr-1.5 h-4 w-4" /> Export PDF
                            </Button>
                        </a>
                        <a href={exportMethod.url({ id: document.id, format: 'docx' })}>
                            <Button variant="outline" className="text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">
                                <FileDown className="mr-1.5 h-4 w-4" /> Export DOCX
                            </Button>
                        </a>
                    </div>
                </div>

                {/* Main Edit Form */}
                <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
                        
                        {/* Sidebar navigation */}
                        <div className="space-y-2">
                            <Button
                                type="button"
                                variant={activeTab === 'info' ? 'default' : 'ghost'}
                                onClick={() => setActiveTab('info')}
                                className="w-full justify-start text-left"
                            >
                                <BookOpen className="h-4 w-4 mr-2" /> Informasi Umum & CPL
                            </Button>
                            <Button
                                type="button"
                                variant={activeTab === 'cpmk' ? 'default' : 'ghost'}
                                onClick={() => setActiveTab('cpmk')}
                                className="w-full justify-start text-left"
                            >
                                <Layers className="h-4 w-4 mr-2" /> Capaian Pembelajaran (CPMK)
                            </Button>
                            <Button
                                type="button"
                                variant={activeTab === 'weeks' ? 'default' : 'ghost'}
                                onClick={() => setActiveTab('weeks')}
                                className="w-full justify-start text-left"
                            >
                                <Layers className="h-4 w-4 mr-2" /> Rencana Mingguan (1-14)
                            </Button>

                            <Card className="mt-6 border border-sidebar-border/50">
                                <CardContent className="p-4 space-y-4">
                                    <div className="space-y-1.5">
                                        <Label htmlFor="status">Status Dokumen</Label>
                                        <Select 
                                            value={data.status} 
                                            onValueChange={(val: 'draft' | 'published') => setData('status', val)}
                                        >
                                            <SelectTrigger>
                                                <SelectValue />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="draft">Draft</SelectItem>
                                                <SelectItem value="published">Published</SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>

                                    {recentlySuccessful && (
                                        <div className="flex items-center space-x-1.5 text-xs text-green-600 dark:text-green-400 font-semibold animate-bounce mt-2">
                                            <CheckCircle2 className="h-4 w-4" />
                                            <span>RPS berhasil disimpan!</span>
                                        </div>
                                    )}

                                    <Button type="submit" disabled={processing} className="w-full bg-blue-600 hover:bg-blue-700 text-white">
                                        <Save className="h-4 w-4 mr-1.5" /> {processing ? 'Menyimpan...' : 'Simpan Perubahan'}
                                    </Button>
                                </CardContent>
                            </Card>
                        </div>

                        {/* Edit Content */}
                        <div className="lg:col-span-3">
                            
                            {/* Tab 1: General Info & CPL (Read Only Details for reference) */}
                            {activeTab === 'info' && (
                                <Card className="border border-sidebar-border/70 dark:border-sidebar-border shadow-lg">
                                    <CardHeader>
                                        <CardTitle>Informasi Umum Mata Kuliah</CardTitle>
                                        <CardDescription>Detil informasi dasar mata kuliah dan CPL prodi yang dibebankan.</CardDescription>
                                    </CardHeader>
                                    <CardContent className="space-y-6">
                                        <div className="grid grid-cols-2 gap-4">
                                            <div>
                                                <span className="text-xs text-muted-foreground block">Nama Mata Kuliah</span>
                                                <span className="font-semibold text-base">{document.course.name}</span>
                                            </div>
                                            <div>
                                                <span className="text-xs text-muted-foreground block">Kode</span>
                                                <span className="font-semibold font-mono text-base">{document.course.code}</span>
                                            </div>
                                            <div>
                                                <span className="text-xs text-muted-foreground block">SKS</span>
                                                <span className="font-semibold text-base">{document.course.sks} SKS</span>
                                            </div>
                                            <div>
                                                <span className="text-xs text-muted-foreground block">Semester Target</span>
                                                <span className="font-semibold text-base">Semester {document.course.semester}</span>
                                            </div>
                                            <div className="col-span-2">
                                                <span className="text-xs text-muted-foreground block">Deskripsi Mata Kuliah</span>
                                                <p className="text-sm text-muted-foreground mt-1 leading-relaxed">{document.course.description}</p>
                                            </div>
                                        </div>

                                        <div className="border-t border-sidebar-border/50 pt-4 space-y-3">
                                            <h4 className="font-semibold text-sm">Graduate Learning Outcomes (CPL) yang Ditargetkan</h4>
                                            <div className="space-y-2">
                                                {document.course.cpls.map(cpl => (
                                                    <div key={cpl.code} className="flex items-start space-x-2.5 p-3 rounded-lg bg-muted/40 border border-sidebar-border/30">
                                                        <Badge variant="outline" className="font-bold shrink-0">{cpl.code}</Badge>
                                                        <div className="text-xs">
                                                            <span className="font-semibold text-neutral-700 dark:text-neutral-300 block mb-0.5">{cpl.type}</span>
                                                            <p className="text-muted-foreground leading-relaxed">{cpl.description}</p>
                                                        </div>
                                                    </div>
                                                ))}
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>
                            )}

                            {/* Tab 2: CPMK Editing */}
                            {activeTab === 'cpmk' && (
                                <Card className="border border-sidebar-border/70 dark:border-sidebar-border shadow-lg">
                                    <CardHeader>
                                        <CardTitle>Capaian Pembelajaran Mata Kuliah (CPMK)</CardTitle>
                                        <CardDescription>Edit pernyataan CPMK dan indikator evaluasi keberhasilan pembelajaran.</CardDescription>
                                    </CardHeader>
                                    <CardContent className="space-y-6">
                                        {data.cpmks.map((cpmk, index) => {
                                            const originalCpmk = initialCpmks[index];
                                            return (
                                                <div key={cpmk.id} className="p-4 rounded-xl border border-sidebar-border/60 bg-muted/10 space-y-4">
                                                    <div className="flex items-center justify-between">
                                                        <Badge className="bg-indigo-500 hover:bg-indigo-600 text-white font-bold">CPMK {index + 1}</Badge>
                                                        {originalCpmk?.cpl && (
                                                            <Badge variant="outline" className="font-mono text-xs">
                                                                Mendukung CPL: {originalCpmk.cpl.code}
                                                            </Badge>
                                                        )}
                                                    </div>
                                                    <div className="space-y-1.5">
                                                        <Label>Pernyataan Capaian Pembelajaran</Label>
                                                        <Textarea 
                                                            value={cpmk.statement}
                                                            onChange={(e) => handleCpmkChange(index, 'statement', e.target.value)}
                                                            rows={2}
                                                        />
                                                    </div>
                                                    <div className="space-y-1.5">
                                                        <Label>Indikator Asesmen</Label>
                                                        <Textarea 
                                                            value={cpmk.indicators}
                                                            onChange={(e) => handleCpmkChange(index, 'indicators', e.target.value)}
                                                            rows={2}
                                                        />
                                                    </div>
                                                </div>
                                            );
                                        })}
                                    </CardContent>
                                </Card>
                            )}

                            {/* Tab 3: Weekly learning plans */}
                            {activeTab === 'weeks' && (
                                <div className="space-y-6">
                                    {data.weeks.map((week, index) => (
                                        <Card key={week.id} className="border border-sidebar-border/70 dark:border-sidebar-border shadow-md overflow-hidden hover:border-blue-500/20 transition-all duration-300">
                                            <CardHeader className="bg-gradient-to-r from-blue-500/5 to-indigo-500/5 py-3.5 border-b border-sidebar-border/40">
                                                <div className="flex justify-between items-center">
                                                    <CardTitle className="text-base font-bold text-neutral-800 dark:text-neutral-200">
                                                        Pertemuan / Minggu {week.week_number}
                                                    </CardTitle>
                                                    <div className="flex items-center space-x-2">
                                                        <Label className="text-xs font-semibold">Bobot Penilaian:</Label>
                                                        <div className="flex items-center space-x-1 w-20">
                                                            <Input
                                                                type="number"
                                                                value={week.weight}
                                                                onChange={(e) => handleWeekChange(index, 'weight', parseInt(e.target.value) || 0)}
                                                                className="h-8 py-1 text-center"
                                                            />
                                                            <span className="text-xs font-semibold">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </CardHeader>
                                            <CardContent className="p-5 space-y-4">
                                                <div className="space-y-1.5">
                                                    <Label className="text-xs font-semibold">Sub-CPMK (Kemampuan Akhir yang Diharapkan)</Label>
                                                    <Textarea
                                                        value={week.sub_cpmk}
                                                        onChange={(e) => handleWeekChange(index, 'sub_cpmk', e.target.value)}
                                                        rows={2}
                                                    />
                                                </div>
                                                
                                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div className="space-y-1.5">
                                                        <Label className="text-xs font-semibold">Metode & Pengalaman Belajar</Label>
                                                        <Textarea
                                                            value={week.learning_methods}
                                                            onChange={(e) => handleWeekChange(index, 'learning_methods', e.target.value)}
                                                            rows={3}
                                                            placeholder="Contoh: Diskusi Kelompok, Pembelajaran Kolaboratif..."
                                                        />
                                                    </div>
                                                    <div className="space-y-1.5">
                                                        <Label className="text-xs font-semibold">Kriteria & Bentuk Penilaian</Label>
                                                        <Textarea
                                                            value={week.assessment_criteria}
                                                            onChange={(e) => handleWeekChange(index, 'assessment_criteria', e.target.value)}
                                                            rows={3}
                                                            placeholder="Contoh: Keaktifan berdiskusi, Kuis tulisan, Rubrik unjuk kerja..."
                                                        />
                                                    </div>
                                                </div>

                                                <div className="space-y-1.5">
                                                    <Label className="text-xs font-semibold">Bahan Kajian / Sumber Belajar</Label>
                                                    <Input
                                                        value={week.resources}
                                                        onChange={(e) => handleWeekChange(index, 'resources', e.target.value)}
                                                        placeholder="Buku acuan, jurnal, slide presentasi..."
                                                    />
                                                </div>
                                            </CardContent>
                                        </Card>
                                    ))}
                                </div>
                            )}

                        </div>
                    </div>
                </form>
            </div>
        </>
    );
}

RpsEdit.layout = {
    breadcrumbs: [
        {
            title: 'RPS OBE',
            href: '/rps',
        },
        {
            title: 'Edit RPS',
            href: '#',
        },
    ],
};
