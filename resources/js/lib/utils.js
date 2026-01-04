import { router } from '@inertiajs/react';
import { clsx } from 'clsx';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';
import { toast } from 'sonner';
import { twMerge } from 'tailwind-merge';

function cn(...inputs) {
    return twMerge(clsx(inputs));
}

function flashMessage(params) {
    return params.props.flash_message;
}

const deleteAction = (url, { closeModal, ...options } = {}) => {
    const defaultOptions = {
        preserveScroll: true,
        preserveState: true,
        method: 'delete',
        onSuccess: (success) => {
            const flash = flashMessage(success);
            if (flash) {
                toast[flash.type](flash.message);
            }
            if (closeModal && typeof closeModal === 'function') {
                closeModal();
            }
        },

        ...options,
    };

    router.delete(url, defaultOptions);
};

const formatDateIndo = (dateString) => {
    if (!dateString) return '-';
    return format(parseISO(dateString), 'eeee, dd MMMM yyyy', {
        locale: id,
    });
};

const formatToRupiah = (amount) => {
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });
    return formatter.format(amount);
};

const BUDGETTYPE = {
    INCOME: 'Penghasilan',
    SAVING: 'Tabungan dan investasi',
    DEBT: 'Cicilan Hutang',
    BILL: 'Tagihan',
    SHOPPING: 'Belanja',
};

const BUDGETTYPEVARIANT = {
    [BUDGETTYPE.INCOME]: 'emerald',
    [BUDGETTYPE.SAVING]: 'orange',
    [BUDGETTYPE.DEBT]: 'red',
    [BUDGETTYPE.BILL]: 'sky',
    [BUDGETTYPE.SHOPPING]: 'purple',
};

const messages = {
    503: {
        title: 'Service Unavailable',
        description: 'Sorry, we are doing some maintenance. Please check back soon',
        status: 503,
    },
    500: {
        title: 'Service Error',
        description: 'Oops, something went wrong',
        status: 500,
    },
    404: {
        title: 'Not Found',
        description: 'Sorry, the page you are looking for cloud bot be found',
        status: 404,
    },
    403: {
        title: 'Forbidden',
        description: 'Sorry, you are forbidden from accessing this page',
        status: 403,
    },
    401: {
        title: 'Unauthorized',
        description: 'Sorry, you are unauthorized to accses this page',
        status: 401,
    },
    429: {
        title: 'To Many Request',
        description: 'Please try again in just a second',
        status: 429,
    },
};

const MONTHTYPE = {
    JANUARI: 'Januari',
    FEBRUARI: 'Februari',
    MARET: 'Maret',
    APRIL: 'April',
    MEI: 'Mei',
    JUNI: 'Juni',
    JULI: 'Juli',
    AGUSTUS: 'Agustus',
    SEPTEMBER: 'September',
    OKTOBER: 'Oktober',
    NOVEMBER: 'November',
    DESEMBER: 'Desember',
};

const MONTHTYPEVARIANT = {
    [MONTHTYPE.JANUARI]: 'fuchsia',
    [MONTHTYPE.FEBRUARI]: 'orange',
    [MONTHTYPE.MARET]: 'emerald',
    [MONTHTYPE.APRIL]: 'sky',
    [MONTHTYPE.MEI]: 'purple',
    [MONTHTYPE.JUNI]: 'rose',
    [MONTHTYPE.JULI]: 'pink',
    [MONTHTYPE.AGUSTUS]: 'red',
    [MONTHTYPE.SEPTEMBER]: 'violet',
    [MONTHTYPE.OKTOBER]: 'blue',
    [MONTHTYPE.NOVEMBER]: 'lime',
    [MONTHTYPE.DESEMBER]: 'teal',
};

const ASSETTYPE = {
    CASH: 'Cash',
    PERSONAL: 'Personal',
    SHORTTERM: 'Investasi Jangka Pendek',
    MIDTERM: 'Investasi Jangka Menengah',
    LONGTERM: 'Investasi Jangka Panjang',
};

const ASSETTYPEVARIANT = {
    [ASSETTYPE.CASH]: 'emerald',
    [ASSETTYPE.PERSONAL]: 'orange',
    [ASSETTYPE.SHORTTERM]: 'red',
    [ASSETTYPE.MIDTERM]: 'sky',
    [ASSETTYPE.LONGTERM]: 'purple',
};

const LIABILITYTYPE = {
    SHORTTERMDEBT: 'Hutang Jangka Pendek',
    MIDTERMDEBT: 'Hutang Jangka Menengah',
    LONGTERMDEBT: 'Hutang Jangka Panjang',
};

const LIABILITYTYPEVARIANT = {
    [LIABILITYTYPE.SHORTTERMDEBT]: 'emerald',
    [LIABILITYTYPE.MIDTERMDEBT]: 'orange',
    [LIABILITYTYPE.LONGTERMDEBT]: 'red',
};

const LIABILITYDESCRIPTION = {
    [LIABILITYTYPE.SHORTTERMDEBT]: 'Tenor 1-5 tahun',
    [LIABILITYTYPE.MIDTERMDEBT]: 'Tenor 5-10 tahun',
    [LIABILITYTYPE.LONGTERMDEBT]: 'Tenor > 10 tahun',
};

export {
    ASSETTYPE,
    ASSETTYPEVARIANT,
    BUDGETTYPE,
    BUDGETTYPEVARIANT,
    cn,
    deleteAction,
    flashMessage,
    formatDateIndo,
    formatToRupiah,
    LIABILITYDESCRIPTION,
    LIABILITYTYPE,
    LIABILITYTYPEVARIANT,
    messages,
    MONTHTYPE,
    MONTHTYPEVARIANT,
};
