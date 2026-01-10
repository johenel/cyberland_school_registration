import React, { useEffect } from 'react'
import { useToastStore, Toast } from '@/stores/toastStore'

export default function ToastContainer() {
    const { toasts, removeToast } = useToastStore()

    return (
        <div className="fixed bottom-5 right-5 flex flex-col gap-2 z-50">
            {toasts.map((toast) => (
                <ToastItem key={toast.id} toast={toast} onClose={() => removeToast(toast.id)} />
            ))}
        </div>
    )
}

function ToastItem({
                       toast,
                       onClose,
                   }: {
    toast: Toast
    onClose: () => void
}) {
    const [show, setShow] = React.useState(false)

    useEffect(() => {
        // Trigger fade-in
        requestAnimationFrame(() => setShow(true))
        const timeout = setTimeout(() => {
            setShow(false)
            setTimeout(onClose, 300) // wait for fade-out animation
        }, toast.duration)
        return () => clearTimeout(timeout)
    }, [])

    const bgColor = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500',
    }[toast.type || 'info']

    return (
        <div
            className={`transform transition-all duration-300 rounded shadow-lg px-4 py-2 text-white cursor-pointer ${
                show ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-4'
            } ${bgColor}`}
            onClick={() => {
                setShow(false)
                setTimeout(onClose, 300)
            }}
        >
            {toast.message}
        </div>
    )
}
