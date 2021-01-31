import React from 'react';

function Footer() {
    return (
        <footer className="py-4 bg-light mt-auto">
            <div className="container-fluid">
                <div className="d-flex align-items-center justify-content-between small">
                    <div className="text-muted">Copyright &copy; Dmytro Kuchura {(new Date()).getFullYear()}</div>
                    <div>
                        <a href="/" target="_blank">WEB</a>
                    </div>
                </div>
            </div>
        </footer>
    );
}

export default Footer;
