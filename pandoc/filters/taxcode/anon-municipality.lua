function Span(span)
    if span.classes:includes('personaldata') and span.classes:includes('taxcode') then
        local content_string = pandoc.utils.stringify(span.content)
        content_string = string.sub(content_string, 1, 11) .. "xxxx" .. string.sub(content_string, 16, string.len(content_string)) 
        span.content = pandoc.Str(content_string)
    end
    return span
    end