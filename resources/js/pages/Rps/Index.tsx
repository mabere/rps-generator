import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { FileText, FileDown, Edit3, Plus, BookOpen, Layers } from 'lucide-react';
import { create, edit, exportMethod } from '@/routes/rps';

interface Course {
    id: number;
    code: string;
    name: string;
    sks: number;
    semester: number;
    description: string;
    study_program: {
        id: number;
        name: string;
        code: string;
    };
}

interface RpsDocument {
    id: number;
    course_id: number;
    status: 'draft' | 'published';
    generated_at: string;
    course: Course;
    user?: {
        name: string;
    };
}

interface Props {
    documents: RpsDocument[];
    courses: Course[];
}

export default function RpsIndex({ documents, courses }: Props) {
    return (
        <>
            <Head title="RPS OBE Generator" />
            <div className="flex-1 space-y-6 p-8 pt-6">
                <div className="flex items-center justify-between space-y-2">
                    <div>
                        <h2 className="text-3xl font-bold tracking-tight bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent dark:from-blue-400 dark:to-indigo-400">
                            RPS OBE Generator
                        </h2>
                        <p className="text-muted-foreground">
                            Buat dan kelola Rencana Pembelajaran Semester berbasis Output-Based Education (OBE) dibantu Gemini AI.
                        </p>
                    </div>
                    <div className="flex items-center space-x-2">
                        <Link href={create.url()}>
                            <Button className="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]">
                                <Plus className="mr-2 h-4 w-4" /> Buat RPS Baru
                            </Button>
                        </Link>
                    </div>
                </div>

                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {documents.length === 0 ? (
                        <Card className="col-span-full border-dashed flex flex-col items-center justify-center p-12 text-center bg-card/50 backdrop-blur-sm">
                            <Layers className="h-12 w-12 text-muted-foreground mb-4 opacity-50" />
                            <h3 className="font-semibold text-lg">Belum ada RPS</h3>
                            <p className="text-sm text-muted-foreground max-w-sm mt-1 mb-6">
                                Anda belum membuat Rencana Pembelajaran Semester apa pun. Mulai buat dengan mengklik tombol di bawah ini.
                            </p>
                            <Link href={create.url()}>
                                <Button variant="outline">
                                    <Plus className="mr-2 h-4 w-4" /> Mulai Generate RPS
                                </Button>
                            </Link>
                        </Card>
                    ) : (
                        documents.map((doc) => (
                            <Card key={doc.id} className="overflow-hidden transition-all duration-300 hover:shadow-xl hover:border-blue-500/30 group bg-card/60 backdrop-blur-md relative border border-sidebar-border/70 dark:border-sidebar-border">
                                <div className="absolute top-0 left-0 w-full h-[3px] bg-gradient-to-r from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity" />
                                <CardHeader className="pb-4">
                                    <div className="flex justify-between items-start">
                                        <Badge variant={doc.status === 'published' ? 'default' : 'secondary'} className="capitalize">
                                            {doc.status}
                                        </Badge>
                                        <span className="text-[10px] text-muted-foreground">
                                            {new Date(doc.generated_at).toLocaleDateString('id-ID')}
                                        </span>
                                    </div>
                                    <CardTitle className="line-clamp-1 group-hover:text-blue-500 transition-colors mt-2 text-xl">
                                        {doc.course.name}
                                    </CardTitle>
                                    <CardDescription className="font-mono text-xs mt-1">
                                        {doc.course.code} &bull; {doc.course.sks} SKS &bull; Semester {doc.course.semester}
                                    </CardDescription>
                                </CardHeader>
                                <CardContent className="pb-4">
                                    <div className="flex items-center space-x-2 text-sm text-muted-foreground mb-3">
                                        <BookOpen className="h-4 w-4 text-blue-500" />
                                        <span className="font-medium text-neutral-800 dark:text-neutral-200">
                                            {doc.course.study_program?.name}
                                        </span>
                                    </div>
                                    <p className="text-xs text-muted-foreground line-clamp-3 bg-muted/40 p-2.5 rounded-lg border border-sidebar-border/30">
                                        {doc.course.description || 'Tidak ada deskripsi mata kuliah.'}
                                    </p>
                                </CardContent>
                                <CardFooter className="pt-2 flex justify-between border-t border-sidebar-border/50 bg-muted/20">
                                    <Link href={edit.url({ id: doc.id })}>
                                        <Button variant="ghost" size="sm" className="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                            <Edit3 className="mr-1.5 h-4 w-4" /> Edit
                                        </Button>
                                    </Link>
                                    <div className="flex items-center space-x-1">
                                        <a href={exportMethod.url({ id: doc.id, format: 'pdf' })} target="_blank" rel="noopener noreferrer">
                                            <Button variant="ghost" size="sm" className="text-rose-600 hover:text-rose-700 dark:text-rose-400">
                                                <FileText className="mr-1.5 h-4 w-4" /> PDF
                                            </Button>
                                        </a>
                                        <a href={exportMethod.url({ id: doc.id, format: 'docx' })}>
                                            <Button variant="ghost" size="sm" className="text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">
                                                <FileDown className="mr-1.5 h-4 w-4" /> DOCX
                                            </Button>
                                        </a>
                                    </div>
                                </CardFooter>
                            </Card>
                        ))
                    )}
                </div>
            </div>
        </>
    );
}

RpsIndex.layout = {
    breadcrumbs: [
        {
            title: 'RPS OBE',
            href: '/rps',
        },
    ],
};
