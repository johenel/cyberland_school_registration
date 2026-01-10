import React, { useEffect, useState } from 'react'
import { useModalStore } from '@/stores/modalStore'

export default function Modal() {
    const { isOpen, content, closeModal } = useModalStore()
    const [show, setShow] = useState(false)
    const [animate, setAnimate] = useState(false) // controls class changes

    // Mount / unmount modal
    useEffect(() => {
        if (isOpen) {
            setShow(true)
            // trigger fade-in in next tick
            setTimeout(() => setAnimate(true), 10)
        } else {
            setAnimate(false)
            const timeout = setTimeout(() => setShow(false), 300) // wait for fade-out
            return () => clearTimeout(timeout)
        }
    }, [isOpen])

    if (!show) return null

    return (
        <div
            className={`fixed inset-0 flex items-center justify-center z-50 transition-opacity duration-300 ${
                animate ? 'opacity-100' : 'opacity-0'
            }`}
            style={{ backgroundColor: 'rgba(0,0,0,0.25)' }}
        >
            <div
                className={`bg-white p-6 rounded shadow-lg max-w-lg w-full transform transition-all duration-300 ${
                    animate ? 'translate-y-0 scale-100' : 'translate-y-4 scale-95'
                }`}
                onClick={(e) => e.stopPropagation()}
            >
                {content}
                <div className="mt-4 text-right">
                    <button
                        className="px-4 py-2 bg-blue-500 text-white rounded"
                        onClick={closeModal}
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    )
}
